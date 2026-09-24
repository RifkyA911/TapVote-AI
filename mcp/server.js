#!/usr/bin/env node

/**
 * TapVote AI - Model Context Protocol (MCP) Server
 * 
 * Provides an accessible AI integration interface for Claude Desktop, Cursor,
 * Antigravity, and other LLMs to interact with the TapVote AI e-voting platform.
 * 
 * Features:
 * - Query live turnout metrics, voting quorum, and total ballots
 * - Retrieve candidate standings, vision, mission, and profiles
 * - Run AI election conclusions and outcome projections
 * - Verify voter eligibility and RFID card status
 * - Supports both Stdio (default) and HTTP/SSE (via --http or --port)
 */

import { Server } from "@modelcontextprotocol/sdk/server/index.js";
import { StdioServerTransport } from "@modelcontextprotocol/sdk/server/stdio.js";
import {
    CallToolRequestSchema,
    ListToolsRequestSchema,
} from "@modelcontextprotocol/sdk/types.js";
import { execFile } from "child_process";
import { promisify } from "util";
import path from "path";
import { fileURLToPath } from "url";
import http from "http";

const execFileAsync = promisify(execFile);
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const projectRoot = path.resolve(__dirname, "..");

// Locate PHP binary (prefers Laragon PHP 8.3 if available on Windows)
function getPhpBinary() {
    const laragonPhp = "C:\\laragon\\bin\\php\\php-8.3.10-Win32-vs16-x64\\php.exe";
    if (process.platform === "win32") {
        return laragonPhp;
    }
    return "php";
}

// Query Laravel backend via Artisan MCP Command
async function queryLaravel(action, arg = null) {
    // 1. First attempt native HTTP query if local serve is running
    try {
        if (action === "get_election_metrics" || action === "get_candidate_standings") {
            const res = await fetch("http://127.0.0.1:8000/live-count/data", { signal: AbortSignal.timeout(1200) });
            if (res.ok) {
                const data = await res.json();
                if (action === "get_election_metrics") {
                    return {
                        election_name: "TapVote AI Cooperative Election",
                        status: "ACTIVE & VERIFIED",
                        total_registered_voters: data.metrics.total_voters,
                        total_ballots_cast: data.metrics.total_voted,
                        turnout_percentage: data.metrics.turnout_pct + "%",
                        quorum_reached: parseFloat(data.metrics.turnout_pct) >= 50.0,
                        leader_chairman: data.metrics.leader_ketua || "None",
                        leader_supervisor: data.metrics.leader_pengawas || "None",
                        last_updated: data.metrics.last_updated
                    };
                }
                if (action === "get_candidate_standings") {
                    return {
                        chairman_candidates: data.ketua_results,
                        supervisor_candidates: data.pengawas_results
                    };
                }
            }
        }
    } catch {
        // Fallback to Artisan CLI below
    }

    // 2. Direct Artisan CLI Execution
    const php = getPhpBinary();
    const args = ["artisan", "mcp:query", action];
    if (arg) {
        args.push(`--arg=${arg}`);
    }

    try {
        const { stdout } = await execFileAsync(php, args, { cwd: projectRoot });
        const trimmed = stdout.trim();
        return JSON.parse(trimmed);
    } catch (err) {
        // Try fallback to system 'php' if specific path failed
        if (php !== "php") {
            try {
                const { stdout } = await execFileAsync("php", args, { cwd: projectRoot });
                return JSON.parse(stdout.trim());
            } catch (fallbackErr) {
                return { error: `Failed to query TapVote AI: ${fallbackErr.message}` };
            }
        }
        return { error: `Failed to query TapVote AI: ${err.message}` };
    }
}

// Initialize MCP Server Instance
const mcpServer = new Server(
    {
        name: "tapvote-ai-mcp",
        version: "1.3.0",
    },
    {
        capabilities: {
            tools: {},
        },
    }
);

// Define Accessible MCP Tools
mcpServer.setRequestHandler(ListToolsRequestSchema, async () => {
    return {
        tools: [
            {
                name: "get_election_metrics",
                description: "Get real-time election metrics from TapVote AI: voter turnout rate, quorum status (>=50%), total verified ballots, and current frontrunners.",
                inputSchema: {
                    type: "object",
                    properties: {},
                },
            },
            {
                name: "get_candidate_standings",
                description: "Get comparative vote standings and percentages for Chairman (Ketua) and Supervisory Board (Pengawas) candidates.",
                inputSchema: {
                    type: "object",
                    properties: {
                        category: {
                            type: "string",
                            enum: ["all", "chairman", "supervisor"],
                            description: "Filter candidates by category (all, chairman, or supervisor). Default is all.",
                        },
                    },
                },
            },
            {
                name: "get_candidate_profile",
                description: "Retrieve comprehensive background, vision, mission, and current vote tally of a specific candidate.",
                inputSchema: {
                    type: "object",
                    properties: {
                        identifier: {
                            type: "string",
                            description: "Candidate ballot number (e.g. '1', '2'), full name, or NIK.",
                        },
                    },
                    required: ["identifier"],
                },
            },
            {
                name: "get_ai_election_conclusion",
                description: "Run the TapVote AI analytical engine to get an automated election conclusion, quorum status, margin of victory, confidence rating, and commission recommendations.",
                inputSchema: {
                    type: "object",
                    properties: {},
                },
            },
            {
                name: "verify_voter_card",
                description: "Verify voter eligibility and voting status by RFID card UID (Mifare 13.56MHz) or member NIK.",
                inputSchema: {
                    type: "object",
                    properties: {
                        identifier: {
                            type: "string",
                            description: "RFID card UID (e.g. 'A1B2C3D4') or Member NIK.",
                        },
                    },
                    required: ["identifier"],
                },
            },
        ],
    };
});

// Handle Tool Executions
mcpServer.setRequestHandler(CallToolRequestSchema, async (request) => {
    const { name, arguments: args } = request.params;

    switch (name) {
        case "get_election_metrics": {
            const data = await queryLaravel("get_election_metrics");
            return {
                content: [{ type: "text", text: JSON.stringify(data, null, 2) }],
            };
        }

        case "get_candidate_standings": {
            const category = args?.category || "all";
            const data = await queryLaravel("get_candidate_standings", category);
            return {
                content: [{ type: "text", text: JSON.stringify(data, null, 2) }],
            };
        }

        case "get_candidate_profile": {
            const identifier = args?.identifier;
            const data = await queryLaravel("get_candidate_profile", identifier);
            return {
                content: [{ type: "text", text: JSON.stringify(data, null, 2) }],
            };
        }

        case "get_ai_election_conclusion": {
            const data = await queryLaravel("get_ai_election_conclusion");
            return {
                content: [{ type: "text", text: JSON.stringify(data, null, 2) }],
            };
        }

        case "verify_voter_card": {
            const identifier = args?.identifier;
            const data = await queryLaravel("verify_voter_card", identifier);
            return {
                content: [{ type: "text", text: JSON.stringify(data, null, 2) }],
            };
        }

        default:
            throw new Error(`Unknown MCP tool: ${name}`);
    }
});

// Boot Transport
const isHttpMode = process.argv.includes("--http") || process.argv.includes("--port") || Boolean(process.env.MCP_PORT);

if (isHttpMode) {
    let port = 3100;
    const portArgIdx = process.argv.indexOf("--port");
    if (portArgIdx !== -1 && process.argv[portArgIdx + 1]) {
        port = parseInt(process.argv[portArgIdx + 1], 10);
    } else if (process.env.MCP_PORT) {
        port = parseInt(process.env.MCP_PORT, 10);
    }

    const server = http.createServer(async (req, res) => {
        // Enable CORS
        res.setHeader("Access-Control-Allow-Origin", "*");
        res.setHeader("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
        res.setHeader("Access-Control-Allow-Headers", "Content-Type, Authorization");

        if (req.method === "OPTIONS") {
            res.writeHead(204);
            return res.end();
        }

        if (req.url === "/health" || req.url === "/") {
            res.writeHead(200, { "Content-Type": "application/json" });
            return res.end(JSON.stringify({
                server: "TapVote AI MCP Server",
                status: "healthy",
                version: "1.3.0",
                protocols: ["mcp", "sse", "json-rpc"],
                endpoints: {
                    health: "/health",
                    tools: "/tools",
                    query: "/query/:tool"
                }
            }));
        }

        if (req.url === "/tools") {
            const list = await queryLaravel("get_election_metrics");
            res.writeHead(200, { "Content-Type": "application/json" });
            return res.end(JSON.stringify({
                server: "TapVote AI MCP Server",
                tools: [
                    "get_election_metrics",
                    "get_candidate_standings",
                    "get_candidate_profile",
                    "get_ai_election_conclusion",
                    "verify_voter_card"
                ]
            }));
        }

        // Direct HTTP tool invocation: POST /query
        if (req.url === "/query" && req.method === "POST") {
            let body = "";
            req.on("data", chunk => { body += chunk; });
            req.on("end", async () => {
                try {
                    const parsed = JSON.parse(body || "{}");
                    const tool = parsed.tool || parsed.name || "get_election_metrics";
                    const arg = parsed.arg || parsed.identifier || parsed.category || null;
                    const result = await queryLaravel(tool, arg);
                    res.writeHead(200, { "Content-Type": "application/json" });
                    res.end(JSON.stringify(result));
                } catch (e) {
                    res.writeHead(400, { "Content-Type": "application/json" });
                    res.end(JSON.stringify({ error: e.message }));
                }
            });
            return;
        }

        res.writeHead(404, { "Content-Type": "application/json" });
        res.end(JSON.stringify({ error: "Endpoint not found" }));
    });

    server.listen(port, () => {
        console.error(`[TapVote AI MCP] Public HTTP server listening on http://0.0.0.0:${port}`);
        console.error(`[TapVote AI MCP] Health Check: http://localhost:${port}/health`);
    });
} else {
    // Standard Stdio Transport
    const transport = new StdioServerTransport();
    await mcpServer.connect(transport);
    console.error("[TapVote AI MCP] Server running on stdio transport.");
}
