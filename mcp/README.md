# TapVote AI - Model Context Protocol (MCP) Server

The **TapVote AI MCP Server** enables Large Language Models (LLMs) and AI coding assistants—such as **Claude Desktop**, **Cursor**, **Google Antigravity**, and **Zed**—to programmatically connect to, monitor, and query live election data from the TapVote AI e-voting platform.

---

## 🌟 Available MCP Tools

| Tool Name | Arguments | Description |
|---|---|---|
| `get_election_metrics` | *(None)* | Returns real-time turnout percentage, verified ballots count, quorum attainment ($\ge 50\%$), and current frontrunners. |
| `get_candidate_standings` | `category`: `"all"` \| `"chairman"` \| `"supervisor"` | Returns detailed vote counts and percentage shares for all candidates. |
| `get_candidate_profile` | `identifier`: Ballot number (e.g. `"1"`), Name, or NIK | Retrieves full biography, official Vision (*Visi*), Mission (*Misi*), and live vote count. |
| `get_ai_election_conclusion` | *(None)* | Executes the analytical AI engine to produce a quorum assessment, victory margin, confidence rating, and commission recommendation. |
| `verify_voter_card` | `identifier`: RFID card UID or Member NIK | Checks if a voter card is registered in the DPT and whether their voting right is still active or already cast. |

---

## 🚀 Running the Server

### 1. Stdio Mode (Recommended for Local AI Clients)
```bash
node mcp/server.js
# or via npm script
npm run mcp
```

### 2. Public HTTP / SSE Mode (For Web & Remote Agents)
```bash
node mcp/server.js --http 3100
# or via npm script
npm run mcp:http
```
Endpoints:
- Health Check: `http://localhost:3100/health`
- Tool Catalog: `http://localhost:3100/tools`
- Direct Tool Invocation: `POST http://localhost:3100/query` (`{"tool": "get_election_metrics"}`)

---

## 🔌 Client Configurations

### Claude Desktop (`claude_desktop_config.json`)
Add the following to your Claude Desktop configuration:

```json
{
  "mcpServers": {
    "tapvote-ai": {
      "command": "node",
      "args": ["C:\\laragon\\www\\TapVote-AI\\mcp\\server.js"],
      "cwd": "C:\\laragon\\www\\TapVote-AI"
    }
  }
}
```

### Cursor & Antigravity IDE (`mcp.json`)
```json
{
  "mcpServers": {
    "tapvote-ai": {
      "command": "node",
      "args": ["./mcp/server.js"]
    }
  }
}
```
