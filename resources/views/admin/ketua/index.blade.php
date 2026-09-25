@extends('layouts.admin')

@section('title', 'Chairman Candidates - TapVote AI')

@section('content')
<div class="space-y-6">

    <!-- Top Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-blue-100 text-blue-800 border border-blue-200">
                    Official Nominees
                </span>
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200">
                    Category: Chairman
                </span>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900">Chairman Candidates Management</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Manage official candidate profiles, vision & mission statements, and real-time tallies.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.reports.ketua.export.pdf') }}" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold border border-slate-300 shadow-2xs transition flex items-center space-x-1.5 cursor-pointer" title="Download Official Vector PDF Roster">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Export PDF Roster</span>
            </a>

            <a href="{{ route('admin.ketua.create') }}" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs transition flex items-center space-x-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Add Chairman Candidate</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Summary Cards -->
    @php
        $totalKetuaVotes = $kandidat->sum('perolehan_suara_count');
        $leader = $kandidat->sortByDesc('perolehan_suara_count')->first();
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-500 font-semibold">Total Candidates</span>
                <strong class="block text-2xl font-black text-slate-900 font-mono">{{ $kandidat->count() }}</strong>
            </div>
            <span class="p-2.5 rounded-xl bg-blue-50 text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </span>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs text-blue-700 font-semibold">Total Votes Received</span>
                <strong class="block text-2xl font-black text-blue-600 font-mono">{{ $totalKetuaVotes }}</strong>
            </div>
            <span class="p-2.5 rounded-xl bg-blue-50 text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs text-emerald-700 font-semibold">Leading Frontrunner</span>
                <strong class="block text-lg font-black text-emerald-700 truncate max-w-[180px]">
                    {{ $leader && $leader->perolehan_suara_count > 0 ? "No. {$leader->nomor_urut} {$leader->nama}" : 'No Votes Yet' }}
                </strong>
            </div>
            <span class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            </span>
        </div>
    </div>

    <!-- Search & Filter Toolbar -->
    <div class="p-4 sm:p-5 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-3.5">
        <div class="flex items-center gap-3">
            <div class="relative flex-1">
                <input 
                    type="text" 
                    id="candidate-search-input" 
                    placeholder="Search candidate by name, NIK, or vision (Press '/' to focus)..."
                    oninput="filterCandidateTable()"
                    class="w-full pl-10 pr-10 py-3 rounded-2xl bg-slate-50/80 border border-slate-200 text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition font-medium"
                >
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <button type="button" onclick="clearCandidateSearch()" class="absolute right-3.5 top-3 text-slate-400 hover:text-slate-600 text-sm font-bold cursor-pointer" title="Clear Search">✕</button>
            </div>
            <div class="hidden md:flex items-center shrink-0">
                <kbd class="px-2.5 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-500 text-[11px] font-bold font-mono">
                    /
                </kbd>
            </div>
        </div>
    </div>

    <!-- Candidate DataTable Container (Unified with DPT Table) -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-2xs overflow-hidden relative">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm" id="candidate-table">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-extrabold uppercase text-[11px] tracking-wider select-none">
                        <th class="py-3 px-2 text-center w-10" title="Drag & Drop prioritize order">Drag</th>
                        <th class="py-3 px-3 text-center w-16">No.</th>
                        <th class="py-3 px-3 text-center w-24">Photo</th>
                        <th class="py-3 px-3">Candidate Identity</th>
                        <th class="py-3 px-3">Vision & Mission</th>
                        <th class="py-3 px-3 text-center w-44">Tally & Percentage</th>
                        <th class="py-3 px-3 text-right w-28">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="candidate-table-body">
                    @forelse($kandidat as $k)
                        @php
                            $fotoKandidat = $k->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($k->nama).'&background=2563eb&color=ffffff&size=400';
                            $pct = $totalKetuaVotes > 0 ? round(($k->perolehan_suara_count / $totalKetuaVotes) * 100, 1) : 0;
                        @endphp
                        <tr draggable="true" class="hover:bg-slate-50/80 transition candidate-row cursor-grab active:cursor-grabbing group" data-nik="{{ $k->nik }}" data-name="{{ strtolower($k->nama) }}" data-visi="{{ strtolower($k->visi) }}">
                            <!-- Drag Handle Column -->
                            <td class="py-3 px-2 text-center text-slate-400 group-hover:text-blue-600 transition select-none font-bold text-base">
                                <span title="Drag to reorder ballot priority">⋮⋮</span>
                            </td>

                            <!-- Ballot Number -->
                            <td class="py-3 px-3 text-center">
                                <span class="w-9 h-9 rounded-xl bg-blue-600 text-white font-black text-sm flex items-center justify-center mx-auto shadow-xs candidate-order-badge">
                                    {{ $k->nomor_urut }}
                                </span>
                            </td>

                            <!-- Photo with Cardless Preview Modal Trigger -->
                            <td class="py-3 px-3 text-center">
                                <button 
                                    type="button" 
                                    onclick="openCardlessPreview('{{ $fotoKandidat }}', '{{ addslashes($k->nama) }}', 'No. {{ $k->nomor_urut }} • Chairman', '{{ $k->nik }}')"
                                    class="group relative w-14 h-18 rounded-xl overflow-hidden bg-slate-900 border-2 border-slate-200 hover:border-blue-500 transition-all duration-300 shadow-2xs hover:shadow-md cursor-pointer inline-block"
                                    title="Click to view cardless high-res photo"
                                >
                                    <img src="{{ $fotoKandidat }}" alt="{{ $k->nama }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                    <div class="absolute inset-0 bg-blue-600/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition duration-200">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                    </div>
                                </button>
                            </td>

                            <!-- Candidate Identity -->
                            <td class="py-3 px-3">
                                <div class="font-extrabold text-slate-900 text-sm sm:text-base">{{ $k->nama }}</div>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 text-[11px] font-mono font-bold border border-blue-200">
                                        NIK: {{ $k->nik }}
                                    </span>
                                </div>
                            </td>

                            <!-- Vision & Mission Preview -->
                            <td class="py-3 px-3 max-w-xs sm:max-w-md">
                                <div class="text-xs text-slate-700 font-semibold line-clamp-2" title="{{ $k->visi }}">
                                    <strong class="text-slate-900">Vision:</strong> {{ $k->visi }}
                                </div>
                                <div class="text-[11px] text-slate-500 line-clamp-1 mt-1">
                                    <strong class="text-slate-700">Mission:</strong> {{ $k->misi }}
                                </div>
                            </td>

                            <!-- Vote Tally & Percentage -->
                            <td class="py-3 px-3 text-center">
                                <div class="flex items-baseline justify-center space-x-1.5">
                                    <span class="text-base sm:text-lg font-black text-blue-700 font-mono">{{ $k->perolehan_suara_count }}</span>
                                    <span class="text-xs text-slate-500 font-bold">Votes</span>
                                    <span class="text-xs font-bold text-slate-600">({{ $pct }}%)</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2 mt-1.5 overflow-hidden">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ $pct }}%;"></div>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-3 text-right">
                                <div class="flex items-center justify-end space-x-1.5">
                                    <a href="{{ route('admin.ketua.edit', $k->nik) }}" class="p-2 rounded-xl bg-slate-100 hover:bg-blue-50 hover:text-blue-700 text-slate-700 transition" title="Edit Candidate">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>

                                    <form action="{{ route('admin.ketua.destroy', $k->nik) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete Chairman candidate {{ $k->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 transition cursor-pointer" title="Delete Candidate">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                No Chairman candidates found. Click "Add Chairman Candidate" to register.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- CARDLESS PHOTO PREVIEW MODAL (FLOATING LIGHTBOX)          -->
<!-- ======================================================== -->
<div id="cardless-photo-modal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-md flex items-center justify-center p-4 transition-all duration-300" onclick="closeCardlessPreview()">
    <div class="relative max-w-md w-full flex flex-col items-center animate-scale-up" onclick="event.stopPropagation()">
        <!-- Close Button Floating Top-Right -->
        <button 
            type="button" 
            onclick="closeCardlessPreview()" 
            class="absolute -top-12 right-0 w-10 h-10 rounded-full bg-white/20 hover:bg-white/40 text-white flex items-center justify-center font-bold text-lg backdrop-blur-md transition cursor-pointer"
            title="Close Preview (Esc)"
        >
            ✕
        </button>

        <!-- Cardless Full-Bleed Photo Frame with Soft Glowing Border -->
        <div class="w-full aspect-[3.5/5] max-h-[520px] rounded-3xl overflow-hidden shadow-2xl ring-4 ring-white/20 bg-slate-950 relative">
            <img id="cardless-modal-img" src="" alt="Candidate Photo" class="w-full h-full object-cover object-top">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-80 pointer-events-none"></div>

            <div class="absolute bottom-5 left-5 right-5 text-white">
                <span id="cardless-modal-badge" class="px-2.5 py-0.5 rounded-lg text-[11px] font-black uppercase tracking-wider bg-blue-600 text-white border border-blue-400/40 inline-block mb-1">
                    No. 1 • Chairman
                </span>
                <h3 id="cardless-modal-name" class="text-2xl font-black text-white tracking-tight">Candidate Name</h3>
                <p id="cardless-modal-nik" class="text-xs text-slate-300 font-mono mt-0.5">NIK: -</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function filterCandidateTable() {
        const query = document.getElementById('candidate-search-input').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.candidate-row');
        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const nik = row.getAttribute('data-nik') || '';
            const visi = row.getAttribute('data-visi') || '';
            if (name.includes(query) || nik.includes(query) || visi.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function clearCandidateSearch() {
        const input = document.getElementById('candidate-search-input');
        input.value = '';
        filterCandidateTable();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
            e.preventDefault();
            document.getElementById('candidate-search-input').focus();
        }
        if (e.key === 'Escape') {
            closeCardlessPreview();
        }
    });

    function openCardlessPreview(imgUrl, name, badgeText, nik) {
        document.getElementById('cardless-modal-img').src = imgUrl;
        document.getElementById('cardless-modal-name').innerText = name;
        document.getElementById('cardless-modal-badge').innerText = badgeText;
        document.getElementById('cardless-modal-nik').innerText = 'NIK: ' + nik;
        document.getElementById('cardless-photo-modal').classList.remove('hidden');
    }

    function closeCardlessPreview() {
        document.getElementById('cardless-photo-modal').classList.add('hidden');
    }

    // HTML5 Drag & Drop Table Row Reordering
    const tbody = document.getElementById('candidate-table-body');
    let draggedRow = null;

    if (tbody) {
        tbody.addEventListener('dragstart', (e) => {
            const row = e.target.closest('tr.candidate-row');
            if (!row) return;
            draggedRow = row;
            e.dataTransfer.effectAllowed = 'move';
            row.classList.add('opacity-40', 'bg-blue-50');
        });

        tbody.addEventListener('dragend', (e) => {
            const row = e.target.closest('tr.candidate-row');
            if (row) row.classList.remove('opacity-40', 'bg-blue-50');
            draggedRow = null;
        });

        tbody.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            const targetRow = e.target.closest('tr.candidate-row');
            if (!targetRow || targetRow === draggedRow) return;

            const rect = targetRow.getBoundingClientRect();
            const next = (e.clientY - rect.top) / (rect.bottom - rect.top) > 0.5;
            tbody.insertBefore(draggedRow, next ? targetRow.nextSibling : targetRow);
        });

        tbody.addEventListener('drop', async (e) => {
            e.preventDefault();
            if (!draggedRow) return;

            // Re-index visual badges
            const rows = Array.from(tbody.querySelectorAll('tr.candidate-row'));
            const orderPayload = [];

            rows.forEach((r, idx) => {
                const newNo = idx + 1;
                const badge = r.querySelector('.candidate-order-badge');
                if (badge) badge.textContent = newNo;
                orderPayload.push({
                    nik: r.getAttribute('data-nik'),
                    nomor_urut: newNo
                });
            });

            // Send reordered list to backend
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const res = await fetch("{{ route('admin.ketua.reorder') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ order: orderPayload })
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    if (window.SoundEffects) window.SoundEffects.success();
                    console.log('Nomor urut kandidat ketua berhasil diperbarui.');
                }
            } catch (err) {
                console.error('Error saving candidate order:', err);
            }
        });
    }
</script>
@endpush
@endsection
