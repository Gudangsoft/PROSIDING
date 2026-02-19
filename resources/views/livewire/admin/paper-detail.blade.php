<div class="max-w-7xl mx-auto py-6 px-4" x-data="{ showActivityLog: false }">

    {{-- ═══ Header Breadcrumb (OJS Style) ═══ --}}
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('admin.papers') }}" class="text-blue-600 hover:underline">{{ $paper->id }}</a>
            <span class="text-gray-400">/</span>
            <a href="{{ route('admin.papers') }}" class="text-blue-700 font-bold hover:underline">{{ $paper->user->name }}</a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-600">{{ Str::limit($paper->title, 80) }}</span>
        </div>
        <div class="flex items-center gap-2">
            <button @click="showActivityLog = !showActivityLog" type="button"
                class="px-3 py-1.5 text-sm font-medium border border-gray-300 rounded hover:bg-gray-50 cursor-pointer">
                Activity Log
            </button>
            <button type="button" class="px-3 py-1.5 text-sm font-medium border border-gray-300 rounded hover:bg-gray-50 cursor-pointer">
                Library
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm" x-data x-init="setTimeout(() => $el.remove(), 4000)">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-3 mb-4 text-sm">{{ session('error') }}</div>
    @endif

    {{-- ═══ Top Level Tabs: Workflow / Publication ═══ --}}
    <div class="border-b border-gray-200 mb-0">
        <nav class="flex space-x-0">
            <button wire:click="setTopTab('workflow')" type="button"
                class="px-5 py-3 text-sm font-medium border-b-2 transition-colors cursor-pointer
                    {{ $topTab === 'workflow' ? 'border-blue-600 text-blue-700' : 'border-transparent text-blue-600 hover:text-blue-800' }}">
                Workflow
            </button>
            <button wire:click="setTopTab('publication')" type="button"
                class="px-5 py-3 text-sm font-medium border-b-2 transition-colors cursor-pointer
                    {{ $topTab === 'publication' ? 'border-blue-600 text-blue-700' : 'border-transparent text-blue-600 hover:text-blue-800' }}">
                Publication
            </button>
        </nav>
    </div>

    @if($topTab === 'workflow')
    {{-- ═══ Workflow Sub-tabs ═══ --}}
    <div class="bg-white border border-t-0 border-gray-200">
        {{-- Sub-tab Navigation --}}
        @php
            $workflowTabs = [
                'submission' => 'Submission',
                'review' => 'Review',
                'copyediting' => 'Copyediting',
                'production' => 'Production',
            ];
            $tabColors = [
                'submission' => 'border-pink-500',
                'review' => 'border-blue-500',
                'copyediting' => 'border-purple-500',
                'production' => 'border-green-500',
            ];
        @endphp
        <div class="border-b border-gray-200 px-5">
            <nav class="flex space-x-0">
                @foreach($workflowTabs as $tabKey => $tabLabel)
                <button wire:click="setWorkflowTab('{{ $tabKey }}')" type="button"
                    class="px-4 py-3 text-sm font-medium border-b-2 transition-colors cursor-pointer
                        {{ $workflowTab === $tabKey
                            ? $tabColors[$tabKey] . ' text-gray-800'
                            : 'border-transparent text-blue-600 hover:text-blue-800' }}">
                    {{ $tabLabel }}
                </button>
                @endforeach

                <div class="ml-auto flex items-center">
                    <button type="button" class="px-3 py-1.5 text-sm font-medium text-blue-600 border border-blue-600 rounded hover:bg-blue-50 flex items-center gap-1 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path d="M12 16v-4m0-4h.01" stroke-width="2" stroke-linecap="round"/></svg>
                        Help
                    </button>
                </div>
            </nav>
        </div>

        {{-- ═══ TAB CONTENT + SIDEBAR ═══ --}}
        <div class="flex flex-col lg:flex-row">
            {{-- Main Content Area --}}
            <div class="flex-1 p-5 border-r border-gray-200 min-w-0">

                {{-- ──── SUBMISSION TAB ──── --}}
                @if($workflowTab === 'submission')
                    {{-- Submission Files --}}
                    <div class="border border-gray-200 rounded mb-5">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                            <h3 class="text-sm font-bold text-gray-800">Submission Files</h3>
                            <div class="flex items-center gap-2">
                                <button type="button" class="px-3 py-1 text-xs font-medium border border-gray-300 rounded hover:bg-gray-100 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round"/></svg>
                                    Search
                                </button>
                                <label class="px-3 py-1 text-xs font-medium border border-gray-300 rounded hover:bg-gray-100 cursor-pointer">
                                    Upload File
                                    <input type="file" wire:model="uploadFile" class="hidden" accept=".pdf,.doc,.docx">
                                </label>
                            </div>
                        </div>
                        <div class="p-4">
                            @if($paper->files->count())
                                @foreach($paper->files as $file)
                                <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/><path d="M14 2v6h6" fill="none" stroke="currentColor" stroke-width="1"/></svg>
                                        <div>
                                            <p class="text-sm text-blue-600 hover:underline cursor-pointer">{{ $file->original_name }}</p>
                                            <p class="text-xs text-gray-400">{{ ucfirst(str_replace('_', ' ', $file->type)) }} &bull; {{ $file->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                        class="text-blue-600 text-xs hover:underline">Download</a>
                                </div>
                                @endforeach
                            @else
                                <p class="text-sm text-gray-400 text-center py-2">No Files</p>
                            @endif

                            {{-- Upload progress --}}
                            @if($uploadFile)
                            <div class="mt-3 flex items-center gap-2">
                                <span class="text-xs text-gray-600">{{ $uploadFile->getClientOriginalName() }}</span>
                        <button wire:click="uploadSubmissionFile" type="button" class="px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 cursor-pointer">
                                <span wire:loading.remove wire:target="uploadSubmissionFile">Save</span>
                                <span wire:loading wire:target="uploadSubmissionFile">Saving...</span>
                            </button>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Pre-Review Discussions --}}
                    <div class="border border-gray-200 rounded">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                            <h3 class="text-sm font-bold text-gray-800">Pre-Review Discussions</h3>
                            <button type="button" wire:click="openDiscussionModal('submission')" class="px-3 py-1 text-xs font-medium border border-gray-300 rounded hover:bg-gray-100 cursor-pointer">
                                Add discussion
                            </button>
                        </div>
                        <div class="p-4">
                            @php $stageDiscussions = $paper->discussions->where('stage', 'submission'); @endphp
                            @if($stageDiscussions->count())
                                <div class="space-y-2">
                                @foreach($stageDiscussions as $disc)
                                    <div class="border border-gray-100 rounded">
                                        <div wire:click="openDiscussion({{ $disc->id }})" class="flex items-center justify-between px-3 py-2 cursor-pointer hover:bg-gray-50 text-xs">
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-gray-800">{{ $disc->subject }}</span>
                                                @if($disc->is_closed)
                                                    <span class="bg-red-100 text-red-700 px-1.5 py-0.5 rounded text-[10px]">Closed</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-3 text-gray-500">
                                                <span>{{ $disc->user->name }}</span>
                                                <span>{{ $disc->messages->count() - 1 }} replies</span>
                                                <span>{{ $disc->latestMessage?->created_at?->diffForHumans() ?? '-' }}</span>
                                            </div>
                                        </div>
                                        @if($activeDiscussionId === $disc->id)
                                        <div class="border-t border-gray-100 px-3 py-3 bg-gray-50">
                                            <div class="space-y-3 max-h-60 overflow-y-auto mb-3">
                                                @foreach($disc->messages as $msg)
                                                <div class="flex gap-2">
                                                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-[10px] font-bold text-blue-700 flex-shrink-0">{{ strtoupper(substr($msg->user->name, 0, 1)) }}</div>
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-xs font-semibold text-gray-800">{{ $msg->user->name }}</span>
                                                            <span class="text-[10px] text-gray-400">{{ $msg->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-xs text-gray-600 mt-0.5">{{ $msg->message }}</p>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            @if(!$disc->is_closed)
                                            <div class="flex gap-2">
                                                <input type="text" wire:model="replyMessage" wire:keydown.enter="sendReply" placeholder="Tulis balasan..." class="flex-1 px-3 py-1.5 border border-gray-300 rounded text-xs">
                                                <button type="button" wire:click="sendReply" class="px-3 py-1.5 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 cursor-pointer">Kirim</button>
                                            </div>
                                            @endif
                                            <div class="mt-2 text-right">
                                                <button type="button" wire:click="toggleCloseDiscussion({{ $disc->id }})" class="text-[10px] text-gray-500 hover:text-gray-700 cursor-pointer">
                                                    {{ $disc->is_closed ? 'Buka Kembali' : 'Tutup Diskusi' }}
                                                </button>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-400 text-center py-2 italic">No Items</p>
                            @endif
                        </div>
                    </div>

                {{-- ──── REVIEW TAB ──── --}}
                @elseif($workflowTab === 'review')
                    {{-- Review Round --}}
                    <div class="mb-4">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="px-3 py-1 text-xs font-bold bg-blue-600 text-white rounded">Round 1</span>
                        </div>
                    </div>

                    {{-- Reviewers Section --}}
                    <div class="border border-gray-200 rounded mb-5">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                            <h3 class="text-sm font-bold text-gray-800">Reviewers</h3>
                        </div>
                        <div class="p-4">
                            {{-- Assign Reviewer --}}
                            <div class="flex gap-2 mb-4">
                                <select wire:model="selectedReviewerId" class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm">
                                    <option value="">-- Select Reviewer --</option>
                                    @foreach($reviewers as $reviewer)
                                        @if(!$paper->reviews->contains('reviewer_id', $reviewer->id))
                                        <option value="{{ $reviewer->id }}">{{ $reviewer->name }} ({{ $reviewer->email }})</option>
                                        @endif
                                    @endforeach
                                </select>
                                <button wire:click="assignReviewer" type="button"
                                    class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 font-medium cursor-pointer">
                                    <span wire:loading.remove wire:target="assignReviewer">Assign</span>
                                    <span wire:loading wire:target="assignReviewer">Assigning...</span>
                                </button>
                            </div>

                            @foreach($paper->reviews as $review)
                            <div class="border border-gray-200 rounded p-4 mb-3 {{ $review->status === 'completed' ? 'bg-green-50 border-green-200' : '' }}">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">{{ $review->reviewer->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $review->reviewer->institution ?? $review->reviewer->email }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        {{ $review->status === 'completed' ? 'bg-green-100 text-green-800' :
                                           ($review->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $review->status)) }}
                                    </span>
                                </div>
                                @if($review->status === 'completed')
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <div class="flex items-center gap-4 text-sm">
                                        <span class="text-gray-500">Recommendation:</span>
                                        <span class="font-semibold
                                            {{ $review->recommendation === 'accept' ? 'text-green-700' :
                                               ($review->recommendation === 'reject' ? 'text-red-700' : 'text-yellow-700') }}">
                                            {{ \App\Models\Review::RECOMMENDATION_LABELS[$review->recommendation] ?? '-' }}
                                        </span>
                                    </div>
                                    @if($review->comments)
                                    <div class="mt-2 bg-white rounded p-3 text-sm text-gray-700 border border-gray-100">
                                        <p class="text-xs text-gray-400 mb-1">Comments for Author:</p>
                                        {{ $review->comments }}
                                    </div>
                                    @endif
                                    @if($review->comments_for_editor)
                                    <div class="mt-2 bg-yellow-50 rounded p-3 text-sm text-yellow-800 border border-yellow-100">
                                        <p class="text-xs text-yellow-600 mb-1">Comments for Editor (Private):</p>
                                        {{ $review->comments_for_editor }}
                                    </div>
                                    @endif
                                    @if($review->review_file_path)
                                    <div class="mt-2 flex items-center gap-3 p-3 bg-blue-50 rounded border border-blue-100">
                                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/><path d="M14 2v6h6" fill="none" stroke="currentColor" stroke-width="1"/></svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-blue-600 font-medium">Reviewed File</p>
                                            <p class="text-sm text-gray-800 truncate">{{ $review->review_file_name }}</p>
                                        </div>
                                        <a href="{{ asset('storage/' . $review->review_file_path) }}" target="_blank" class="text-blue-600 text-xs font-medium hover:underline">Download</a>
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                            @endforeach

                            @if($paper->reviews->isEmpty())
                                <p class="text-sm text-gray-400 text-center py-2">No reviewers assigned yet.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Review Discussions --}}
                    <div class="border border-gray-200 rounded">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                            <h3 class="text-sm font-bold text-gray-800">Review Discussions</h3>
                            <button type="button" wire:click="openDiscussionModal('review')" class="px-3 py-1 text-xs font-medium border border-gray-300 rounded hover:bg-gray-100 cursor-pointer">
                                Add discussion
                            </button>
                        </div>
                        <div class="p-4">
                            @php $stageDiscussions = $paper->discussions->where('stage', 'review'); @endphp
                            @if($stageDiscussions->count())
                                <div class="space-y-2">
                                @foreach($stageDiscussions as $disc)
                                    <div class="border border-gray-100 rounded">
                                        <div wire:click="openDiscussion({{ $disc->id }})" class="flex items-center justify-between px-3 py-2 cursor-pointer hover:bg-gray-50 text-xs">
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-gray-800">{{ $disc->subject }}</span>
                                                @if($disc->is_closed)
                                                    <span class="bg-red-100 text-red-700 px-1.5 py-0.5 rounded text-[10px]">Closed</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-3 text-gray-500">
                                                <span>{{ $disc->user->name }}</span>
                                                <span>{{ $disc->messages->count() - 1 }} replies</span>
                                                <span>{{ $disc->latestMessage?->created_at?->diffForHumans() ?? '-' }}</span>
                                            </div>
                                        </div>
                                        @if($activeDiscussionId === $disc->id)
                                        <div class="border-t border-gray-100 px-3 py-3 bg-gray-50">
                                            <div class="space-y-3 max-h-60 overflow-y-auto mb-3">
                                                @foreach($disc->messages as $msg)
                                                <div class="flex gap-2">
                                                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-[10px] font-bold text-blue-700 flex-shrink-0">{{ strtoupper(substr($msg->user->name, 0, 1)) }}</div>
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-xs font-semibold text-gray-800">{{ $msg->user->name }}</span>
                                                            <span class="text-[10px] text-gray-400">{{ $msg->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-xs text-gray-600 mt-0.5">{{ $msg->message }}</p>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            @if(!$disc->is_closed)
                                            <div class="flex gap-2">
                                                <input type="text" wire:model="replyMessage" wire:keydown.enter="sendReply" placeholder="Tulis balasan..." class="flex-1 px-3 py-1.5 border border-gray-300 rounded text-xs">
                                                <button type="button" wire:click="sendReply" class="px-3 py-1.5 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 cursor-pointer">Kirim</button>
                                            </div>
                                            @endif
                                            <div class="mt-2 text-right">
                                                <button type="button" wire:click="toggleCloseDiscussion({{ $disc->id }})" class="text-[10px] text-gray-500 hover:text-gray-700 cursor-pointer">
                                                    {{ $disc->is_closed ? 'Buka Kembali' : 'Tutup Diskusi' }}
                                                </button>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-400 text-center py-2 italic">No Items</p>
                            @endif
                        </div>
                    </div>

                {{-- ──── COPYEDITING TAB ──── --}}
                @elseif($workflowTab === 'copyediting')
                    <div class="border border-gray-200 rounded mb-5">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                            <h3 class="text-sm font-bold text-gray-800">Draft Files</h3>
                            <label class="px-3 py-1 text-xs font-medium border border-gray-300 rounded hover:bg-gray-100 cursor-pointer">
                                Upload File
                                <input type="file" wire:model="copyeditFile" class="hidden" accept=".pdf,.doc,.docx">
                            </label>
                        </div>
                        <div class="p-4">
                            @php $copyeditFiles = $paper->files->whereIn('type', ['full_paper', 'copyedit_draft']); @endphp
                            @if($copyeditFiles->count())
                                @foreach($copyeditFiles as $file)
                                <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/><path d="M14 2v6h6" fill="none" stroke="currentColor" stroke-width="1"/></svg>
                                        <div>
                                            <p class="text-sm text-blue-600">{{ $file->original_name }}</p>
                                            <p class="text-xs text-gray-400">{{ ucfirst(str_replace('_', ' ', $file->type)) }} &bull; {{ $file->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="text-blue-600 text-xs hover:underline">Download</a>
                                </div>
                                @endforeach
                            @else
                                <p class="text-sm text-gray-400 text-center py-2">No Files</p>
                            @endif

                            {{-- Upload progress --}}
                            @if($copyeditFile)
                            <div class="mt-3 flex items-center gap-2">
                                <span class="text-xs text-gray-600">{{ $copyeditFile->getClientOriginalName() }}</span>
                                <button wire:click="uploadCopyeditFile" type="button" class="px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 cursor-pointer">
                                    <span wire:loading.remove wire:target="uploadCopyeditFile">Save</span>
                                    <span wire:loading wire:target="uploadCopyeditFile">Saving...</span>
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Copyediting Discussions --}}
                    <div class="border border-gray-200 rounded">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                            <h3 class="text-sm font-bold text-gray-800">Copyediting Discussions</h3>
                            <button type="button" wire:click="openDiscussionModal('copyediting')" class="px-3 py-1 text-xs font-medium border border-gray-300 rounded hover:bg-gray-100 cursor-pointer">
                                Add discussion
                            </button>
                        </div>
                        <div class="p-4">
                            @php $stageDiscussions = $paper->discussions->where('stage', 'copyediting'); @endphp
                            @if($stageDiscussions->count())
                                <div class="space-y-2">
                                @foreach($stageDiscussions as $disc)
                                    <div class="border border-gray-100 rounded">
                                        <div wire:click="openDiscussion({{ $disc->id }})" class="flex items-center justify-between px-3 py-2 cursor-pointer hover:bg-gray-50 text-xs">
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-gray-800">{{ $disc->subject }}</span>
                                                @if($disc->is_closed)
                                                    <span class="bg-red-100 text-red-700 px-1.5 py-0.5 rounded text-[10px]">Closed</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-3 text-gray-500">
                                                <span>{{ $disc->user->name }}</span>
                                                <span>{{ $disc->messages->count() - 1 }} replies</span>
                                                <span>{{ $disc->latestMessage?->created_at?->diffForHumans() ?? '-' }}</span>
                                            </div>
                                        </div>
                                        @if($activeDiscussionId === $disc->id)
                                        <div class="border-t border-gray-100 px-3 py-3 bg-gray-50">
                                            <div class="space-y-3 max-h-60 overflow-y-auto mb-3">
                                                @foreach($disc->messages as $msg)
                                                <div class="flex gap-2">
                                                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-[10px] font-bold text-blue-700 flex-shrink-0">{{ strtoupper(substr($msg->user->name, 0, 1)) }}</div>
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-xs font-semibold text-gray-800">{{ $msg->user->name }}</span>
                                                            <span class="text-[10px] text-gray-400">{{ $msg->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-xs text-gray-600 mt-0.5">{{ $msg->message }}</p>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            @if(!$disc->is_closed)
                                            <div class="flex gap-2">
                                                <input type="text" wire:model="replyMessage" wire:keydown.enter="sendReply" placeholder="Tulis balasan..." class="flex-1 px-3 py-1.5 border border-gray-300 rounded text-xs">
                                                <button type="button" wire:click="sendReply" class="px-3 py-1.5 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 cursor-pointer">Kirim</button>
                                            </div>
                                            @endif
                                            <div class="mt-2 text-right">
                                                <button type="button" wire:click="toggleCloseDiscussion({{ $disc->id }})" class="text-[10px] text-gray-500 hover:text-gray-700 cursor-pointer">
                                                    {{ $disc->is_closed ? 'Buka Kembali' : 'Tutup Diskusi' }}
                                                </button>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-400 text-center py-2 italic">No Items</p>
                            @endif
                        </div>
                    </div>

                {{-- ──── PRODUCTION TAB ──── --}}
                @elseif($workflowTab === 'production')
                    {{-- Payment Section --}}
                    <div class="border border-gray-200 rounded mb-5">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                            <h3 class="text-sm font-bold text-gray-800">Payment</h3>
                        </div>
                        <div class="p-4">
                            @if(!$paper->payment)
                                <div class="flex gap-3 items-end">
                                    <div class="flex-1">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Amount (Rp)</label>
                                        <input type="number" wire:model="invoiceAmount" class="w-full px-3 py-2 border border-gray-300 rounded text-sm" placeholder="500000">
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                                        <input type="text" wire:model="invoiceDescription" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                                    </div>
                                    <button wire:click="createInvoice" type="button" class="px-4 py-2 bg-green-600 text-white rounded text-sm hover:bg-green-700 font-medium cursor-pointer">
                                    <span wire:loading.remove wire:target="createInvoice">Create Invoice</span>
                                    <span wire:loading wire:target="createInvoice">Creating...</span>
                                </button>
                                </div>
                                {{-- Info Rekening --}}
                                @php
                                    $pConf = $paper->conference;
                                    $pBankName = $pConf->payment_bank_name ?? null;
                                    $pBankAccount = $pConf->payment_bank_account ?? null;
                                    $pAccountHolder = $pConf->payment_account_holder ?? null;
                                @endphp
                                @if($pBankName || $pBankAccount)
                                <div class="mt-3 bg-blue-50 border border-blue-200 rounded p-3">
                                    <p class="text-xs font-bold text-blue-800 mb-1">Rekening Tujuan Pembayaran:</p>
                                    <div class="text-xs space-y-0.5">
                                        @if($pBankName)<p>Bank: <span class="font-medium">{{ $pBankName }}</span></p>@endif
                                        @if($pBankAccount)<p>No. Rekening: <span class="font-mono font-bold">{{ $pBankAccount }}</span></p>@endif
                                        @if($pAccountHolder)<p>a.n. <span class="font-medium">{{ $pAccountHolder }}</span></p>@endif
                                    </div>
                                </div>
                                @endif
                            @else
                                <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                                    <div>
                                        <span class="text-gray-500 text-xs">Invoice Number:</span>
                                        <p class="font-mono font-medium">{{ $paper->payment->invoice_number }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 text-xs">Amount:</span>
                                        <p class="font-bold text-lg">Rp {{ number_format($paper->payment->amount, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 text-xs">Status:</span>
                                        <p>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                {{ $paper->payment->status === 'verified' ? 'bg-green-100 text-green-800' :
                                                   ($paper->payment->status === 'uploaded' ? 'bg-blue-100 text-blue-800' :
                                                   ($paper->payment->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                                {{ ucfirst($paper->payment->status) }}
                                            </span>
                                        </p>
                                    </div>
                                    @if($paper->payment->payment_proof)
                                    <div>
                                        <span class="text-gray-500 text-xs">Payment Proof:</span>
                                        <p><a href="{{ asset('storage/' . $paper->payment->payment_proof) }}" target="_blank" class="text-blue-600 text-sm hover:underline">View Proof</a></p>
                                    </div>
                                    @endif
                                </div>

                                @if($paper->payment->status === 'uploaded')
                                <div class="flex gap-2">
                                    <button wire:click="verifyPayment('verify')" type="button" wire:confirm="Verifikasi pembayaran ini?"
                                        class="flex-1 bg-green-600 text-white py-2 rounded text-sm hover:bg-green-700 font-medium cursor-pointer">
                                        <span wire:loading.remove wire:target="verifyPayment('verify')">✓ Verify Payment</span>
                                        <span wire:loading wire:target="verifyPayment('verify')">Processing...</span>
                                    </button>
                                    <button wire:click="verifyPayment('reject')" type="button" wire:confirm="Tolak pembayaran ini?"
                                        class="flex-1 bg-red-600 text-white py-2 rounded text-sm hover:bg-red-700 font-medium cursor-pointer">
                                        <span wire:loading.remove wire:target="verifyPayment('reject')">✗ Reject Payment</span>
                                        <span wire:loading wire:target="verifyPayment('reject')">Processing...</span>
                                    </button>
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- Deliverables --}}
                    <div class="border border-gray-200 rounded mb-5">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                            <h3 class="text-sm font-bold text-gray-800">Production Files</h3>
                        </div>
                        <div class="p-4">
                            {{-- Author Deliverables --}}
                            <h4 class="text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">From Author</h4>
                            @if($authorDeliverables->count())
                                @foreach($authorDeliverables as $d)
                                <div class="flex items-center justify-between py-2 border-b border-gray-50">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ \App\Models\Deliverable::TYPE_LABELS[$d->type] ?? $d->type }}</p>
                                        <p class="text-xs text-gray-400">{{ $d->original_name }}</p>
                                    </div>
                                    <a href="{{ asset('storage/' . $d->file_path) }}" target="_blank" class="text-blue-600 text-xs hover:underline">Download</a>
                                </div>
                                @endforeach
                            @else
                                <p class="text-sm text-gray-400 py-2 italic">No items from author yet.</p>
                            @endif

                            {{-- Admin Send Files --}}
                            <h4 class="text-xs font-bold text-gray-600 uppercase tracking-wide mt-4 mb-2">Send to Author</h4>
                            @foreach(['prosiding_book' => 'Buku Prosiding', 'certificate' => 'Sertifikat'] as $type => $label)
                            <div class="border border-gray-100 rounded p-3 mb-2">
                                @php
                                    $existing = $adminDeliverables->firstWhere('type', $type);
                                    $fileProp = $type === 'prosiding_book' ? 'prosidingBookFile' : 'certificateFile';
                                @endphp
                                <div class="flex justify-between items-center mb-2">
                                    <h5 class="text-sm font-medium text-gray-800">{{ $label }}</h5>
                                    @if($existing)
                                        <span class="text-green-600 text-xs font-medium">✓ Sent {{ $existing->sent_at?->format('d M Y') }}</span>
                                    @endif
                                </div>

                                @if($existing)
                                {{-- Show existing file info --}}
                                <div class="bg-green-50 border border-green-200 rounded p-2.5 mb-2">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span class="text-xs text-gray-700 truncate">{{ $existing->original_name }}</span>
                                        </div>
                                        <a href="{{ asset('storage/' . $existing->file_path) }}" target="_blank" class="text-blue-600 text-xs hover:underline flex-shrink-0 ml-2">Download</a>
                                    </div>
                                </div>
                                {{-- Replace file (collapsible) --}}
                                <div x-data="{ showReplace: false }">
                                    <button @click="showReplace = !showReplace" type="button" class="text-xs text-gray-500 hover:text-gray-700 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        <span x-text="showReplace ? 'Batal' : 'Ganti File'"></span>
                                    </button>
                                    <div x-show="showReplace" x-cloak class="mt-2 flex gap-2">
                                        <input type="file" wire:model="{{ $fileProp }}" class="flex-1 text-xs" accept=".pdf">
                                        <button wire:click="sendDeliverable('{{ $type }}')" type="button" class="px-3 py-1 bg-purple-600 text-white rounded text-xs hover:bg-purple-700 cursor-pointer">
                                            <span wire:loading.remove wire:target="sendDeliverable('{{ $type }}')">Replace</span>
                                            <span wire:loading wire:target="sendDeliverable('{{ $type }}')">Sending...</span>
                                        </button>
                                    </div>
                                    @error($fileProp) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                @else
                                {{-- Upload new file --}}
                                <div class="flex gap-2">
                                    <input type="file" wire:model="{{ $fileProp }}" class="flex-1 text-xs" accept=".pdf">
                                    <button wire:click="sendDeliverable('{{ $type }}')" type="button" class="px-3 py-1 bg-purple-600 text-white rounded text-xs hover:bg-purple-700 cursor-pointer">
                                        <span wire:loading.remove wire:target="sendDeliverable('{{ $type }}')">Send</span>
                                        <span wire:loading wire:target="sendDeliverable('{{ $type }}')">Sending...</span>
                                    </button>
                                </div>
                                @error($fileProp) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- ═══ RIGHT SIDEBAR ═══ --}}
            <div class="w-full lg:w-72 flex-shrink-0 p-5 space-y-4">

                {{-- Stage-based Actions --}}
                @if($workflowTab === 'submission')
                    {{-- Submission Stage Actions --}}
                    <button wire:click="openSendToReview" type="button"
                        class="w-full py-2.5 bg-blue-700 text-white rounded text-sm font-semibold hover:bg-blue-800 shadow-sm transition-colors cursor-pointer">
                        <span wire:loading.remove wire:target="openSendToReview">Send to Review</span>
                        <span wire:loading wire:target="openSendToReview">Loading...</span>
                    </button>
                    <button wire:click="acceptAndSkipReview" type="button"
                        class="w-full py-2 border border-gray-300 rounded text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors cursor-pointer">
                        <span wire:loading.remove wire:target="acceptAndSkipReview">Accept and Skip Review</span>
                        <span wire:loading wire:target="acceptAndSkipReview">Processing...</span>
                    </button>
                    <button wire:click="openDeclineModal" type="button"
                        class="w-full py-2 border border-gray-300 rounded text-sm font-medium text-red-600 hover:bg-red-50 transition-colors cursor-pointer">
                        Decline Submission
                    </button>

                @elseif($workflowTab === 'review')
                    {{-- Review Stage Actions --}}
                    <button wire:click="acceptSubmission" type="button" wire:loading.attr="disabled"
                        class="w-full py-2.5 bg-green-600 text-white rounded text-sm font-semibold hover:bg-green-700 shadow-sm transition-all disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                        <span wire:loading.remove wire:target="acceptSubmission">Accept Submission</span>
                        <span wire:loading wire:target="acceptSubmission">Processing...</span>
                    </button>
                    <button wire:click="requestRevision" type="button" wire:loading.attr="disabled" wire:confirm="Apakah Anda yakin meminta revisi untuk paper ini?"
                        class="w-full py-2 border border-gray-300 rounded text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                        <span wire:loading.remove wire:target="requestRevision">Request Revisions</span>
                        <span wire:loading wire:target="requestRevision">Processing...</span>
                    </button>
                    <button wire:click="rejectSubmission" type="button" wire:loading.attr="disabled" wire:confirm="Apakah Anda yakin ingin menolak paper ini?"
                        class="w-full py-2 border border-gray-300 rounded text-sm font-medium text-red-600 hover:bg-red-50 transition-all disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                        <span wire:loading.remove wire:target="rejectSubmission">Decline Submission</span>
                        <span wire:loading wire:target="rejectSubmission">Processing...</span>
                    </button>
                    
                    @if($paper->reviews->where('status', 'completed')->count() === 0 && $paper->reviews->count() > 0)
                    <div class="bg-blue-50 border border-blue-200 rounded p-3 text-xs text-blue-700 mt-2">
                        <p class="font-medium mb-1">ℹ️ Review Status</p>
                        <p>{{ $paper->reviews->where('status', 'completed')->count() }} of {{ $paper->reviews->count() }} reviews completed.</p>
                    </div>
                    @endif

                @elseif($workflowTab === 'copyediting')
                    <button wire:click="$set('workflowTab', 'production')" type="button"
                        class="w-full py-2.5 bg-green-600 text-white rounded text-sm font-semibold hover:bg-green-700 shadow-sm cursor-pointer">
                        Send to Production
                    </button>

                @elseif($workflowTab === 'production')
                    @if($paper->status !== 'completed')
                    <button wire:click="openPublishModal" type="button"
                        class="w-full py-2.5 bg-green-600 text-white rounded text-sm font-semibold hover:bg-green-700 shadow-sm cursor-pointer">
                        Schedule for Publication
                    </button>
                    @else
                    <div class="bg-green-50 border border-green-200 rounded p-3 text-xs text-green-700 text-center">
                        <svg class="w-6 h-6 mx-auto mb-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round"/></svg>
                        <p class="font-medium">Published</p>
                    </div>
                    @if($paper->article_link)
                    <a href="{{ $paper->article_link }}" target="_blank" class="mt-2 flex items-center justify-center gap-1.5 text-xs text-blue-600 hover:text-blue-800 font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Lihat Artikel
                    </a>
                    @endif
                    <div class="mt-2">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Link Artikel</label>
                        <div class="flex gap-1">
                            <input wire:model="publishArticleLink" type="url" placeholder="https://..." class="flex-1 px-2 py-1.5 border border-gray-300 rounded text-xs">
                            <button wire:click="updateArticleLink" type="button" class="px-2 py-1.5 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 cursor-pointer">
                                <span wire:loading.remove wire:target="updateArticleLink">Simpan</span>
                                <span wire:loading wire:target="updateArticleLink">...</span>
                            </button>
                        </div>
                        @error('publishArticleLink') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    @endif
                @endif

                {{-- Status Change --}}
                <div class="border border-gray-200 rounded">
                    <div class="px-3 py-2 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wide">Status</h3>
                    </div>
                    <div class="p-3 space-y-2">
                        <select wire:model="newStatus" class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs">
                            @foreach(\App\Models\Paper::STATUS_LABELS as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <textarea wire:model="editorNotes" rows="2" class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs" placeholder="Editor notes..."></textarea>
                        <button wire:click="updateStatus" type="button" class="w-full py-1.5 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 font-medium cursor-pointer">
                            <span wire:loading.remove wire:target="updateStatus">Update</span>
                            <span wire:loading wire:target="updateStatus">Updating...</span>
                        </button>
                    </div>
                </div>

                {{-- Participants --}}
                <div class="border border-gray-200 rounded">
                    <div class="flex items-center justify-between px-3 py-2 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wide">Participants</h3>
                    </div>
                    <div class="p-3 text-xs space-y-3">
                        {{-- Editor --}}
                        <div>
                            <p class="font-bold text-gray-700 mb-1">Editor</p>
                            @if($paper->assignedEditor)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold">{{ strtoupper(substr($paper->assignedEditor->name, 0, 1)) }}</span>
                                    <span class="text-gray-700">{{ $paper->assignedEditor->name }}</span>
                                </div>
                                <button wire:click="unassignEditor" wire:confirm="Hapus editor dari paper ini?" type="button" class="text-red-400 hover:text-red-600 cursor-pointer" title="Hapus editor">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            @else
                            <button wire:click="openAssignEditorModal" type="button" class="w-full py-1.5 border border-dashed border-blue-300 text-blue-600 rounded text-xs hover:bg-blue-50 cursor-pointer flex items-center justify-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Assign Editor
                            </button>
                            @endif
                        </div>

                        {{-- Author --}}
                        <div>
                            <p class="font-bold text-gray-700 mb-1">Author</p>
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-xs font-bold">{{ strtoupper(substr($paper->user->name, 0, 1)) }}</span>
                                <span class="text-gray-600">{{ $paper->user->name }}</span>
                            </div>
                        </div>

                        {{-- Reviewers --}}
                        @if($paper->reviews->count())
                        <div>
                            <p class="font-bold text-gray-700 mb-1">Reviewers</p>
                            @foreach($paper->reviews as $review)
                            <div class="flex items-center gap-2 mb-1">
                                <span class="w-6 h-6 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center text-xs font-bold">{{ strtoupper(substr($review->reviewer->name, 0, 1)) }}</span>
                                <span class="text-gray-600">{{ $review->reviewer->name }}</span>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Paper Info --}}
                <div class="border border-gray-200 rounded">
                    <div class="px-3 py-2 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wide">Submission Info</h3>
                    </div>
                    <div class="p-3 text-xs space-y-1.5">
                        <div><span class="text-gray-500">Submitted:</span> {{ $paper->submitted_at?->format('M d, Y') ?? '-' }}</div>
                        <div><span class="text-gray-500">Last modified:</span> {{ $paper->updated_at->format('M d, Y') }}</div>
                        @if($paper->abstract)
                        <div class="mt-2">
                            <span class="text-gray-500">Abstract:</span>
                            <p class="mt-1 text-gray-700 line-clamp-4">{{ $paper->abstract }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @elseif($topTab === 'publication')
    {{-- ═══ PUBLICATION TAB ═══ --}}
    <div class="bg-white border border-t-0 border-gray-200 rounded-b-lg p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                {{-- Title & Abstract --}}
                <div class="border border-gray-200 rounded">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-sm font-bold text-gray-800">Title & Abstract</h3>
                    </div>
                    <div class="p-4 space-y-3 text-sm">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Title</label>
                            <p class="text-gray-800 font-medium">{{ $paper->title }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Abstract</label>
                            <p class="text-gray-700 whitespace-pre-line text-sm">{{ $paper->abstract }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Keywords</label>
                            <p class="text-gray-700">{{ $paper->keywords ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Contributors --}}
                <div class="border border-gray-200 rounded">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-sm font-bold text-gray-800">Contributors</h3>
                    </div>
                    <div class="p-4 text-sm">
                        <div class="flex items-center gap-3 py-2">
                            <div class="w-8 h-8 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center text-xs font-bold">
                                {{ strtoupper(substr($paper->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $paper->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $paper->user->institution ?? '' }} &bull; {{ $paper->user->email }}</p>
                            </div>
                        </div>
                        @if($paper->authors_meta)
                            @foreach($paper->authors_meta as $author)
                            <div class="flex items-center gap-3 py-2 border-t border-gray-50">
                                <div class="w-8 h-8 bg-gray-100 text-gray-600 rounded-full flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($author['name'] ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $author['name'] ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">{{ $author['institution'] ?? '' }} &bull; {{ $author['email'] ?? '' }}</p>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            {{-- Publication Sidebar --}}
            <div class="space-y-4">
                <div class="border border-gray-200 rounded">
                    <div class="px-3 py-2 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wide">Submission Status</h3>
                    </div>
                    <div class="p-3 text-sm text-center">
                        @php
                            $badgeClasses = match($paper->status) {
                                'accepted', 'completed' => 'bg-green-100 text-green-800 border-green-200',
                                'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                'in_review' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                default => 'bg-blue-100 text-blue-800 border-blue-200',
                            };
                        @endphp
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold border {{ $badgeClasses }}">
                            {{ $paper->status_label }}
                        </span>
                    </div>
                </div>

                <div class="border border-gray-200 rounded">
                    <div class="px-3 py-2 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wide">Timeline</h3>
                    </div>
                    <div class="p-3 text-xs space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Submitted:</span>
                            <span>{{ $paper->submitted_at?->format('M d, Y H:i') ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Accepted:</span>
                            <span>{{ $paper->accepted_at?->format('M d, Y H:i') ?? '-' }}</span>
                        </div>
                        @if($paper->payment)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Paid:</span>
                            <span>{{ $paper->payment->paid_at?->format('M d, Y H:i') ?? '-' }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══ SEND TO REVIEW MODAL ═══ --}}
    @if($showSendToReviewModal)
    <div class="fixed inset-0 bg-black/50 flex items-start justify-center pt-20 z-50" wire:click.self="closeSendToReview">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg" @click.stop>
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Send to Review</h3>
                <button wire:click="closeSendToReview" type="button" class="text-gray-400 hover:text-gray-600 text-xl cursor-pointer">&times;</button>
            </div>
            <div class="p-5">
                <p class="text-sm text-gray-600 mb-4">Select files below to send them to the review stage.</p>

                <div class="border border-gray-200 rounded mb-4">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                        <h4 class="text-sm font-bold text-gray-800">Submission Files</h4>
                        <div class="flex items-center gap-2">
                            <button type="button" class="px-2 py-1 text-xs font-medium border border-gray-300 rounded hover:bg-gray-100 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round"/></svg>
                                Search
                            </button>
                            <button type="button" class="px-2 py-1 text-xs font-medium border border-gray-300 rounded hover:bg-gray-100">Upload File</button>
                        </div>
                    </div>
                    <div class="p-4">
                        @if($paper->files->count())
                            @foreach($paper->files as $file)
                            <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 px-2 rounded {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                                <input type="checkbox" wire:model="selectedFileIds" value="{{ $file->id }}" class="rounded border-gray-300 text-blue-600">
                                <div>
                                    <p class="text-sm text-gray-800">{{ $file->original_name }}</p>
                                    <p class="text-xs text-gray-400">{{ ucfirst(str_replace('_', ' ', $file->type)) }}</p>
                                </div>
                            </label>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-400 text-center py-2 italic">No Files</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-2 px-5 py-4 border-t border-gray-200 bg-gray-50">
                <button wire:click="sendToReview" type="button" class="px-4 py-2 text-sm font-medium border border-blue-600 text-blue-700 rounded hover:bg-blue-50 cursor-pointer">
                    <span wire:loading.remove wire:target="sendToReview">Send to Review</span>
                    <span wire:loading wire:target="sendToReview">Processing...</span>
                </button>
                <button wire:click="closeSendToReview" type="button" class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-800 cursor-pointer">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══ ASSIGN EDITOR MODAL ═══ --}}
    @if($showAssignEditorModal)
    <div class="fixed inset-0 bg-black/50 flex items-start justify-center pt-20 z-50" wire:click.self="closeAssignEditorModal">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md" @click.stop>
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Assign Editor</h3>
                <button wire:click="closeAssignEditorModal" type="button" class="text-gray-400 hover:text-gray-600 text-xl cursor-pointer">&times;</button>
            </div>
            <div class="p-5">
                <p class="text-sm text-gray-600 mb-4">Pilih editor yang akan menangani paper ini.</p>
                <label class="block text-sm font-medium text-gray-700 mb-2">Editor</label>
                <select wire:model="selectedEditorId" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="">-- Pilih Editor --</option>
                    @foreach($editors as $editor)
                    <option value="{{ $editor->id }}">{{ $editor->name }} ({{ $editor->email }})</option>
                    @endforeach
                </select>
                @error('selectedEditorId')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="flex justify-end gap-2 px-5 py-4 border-t border-gray-200 bg-gray-50">
                <button wire:click="assignEditor" type="button" class="px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded hover:bg-blue-700 cursor-pointer">
                    <span wire:loading.remove wire:target="assignEditor">Assign</span>
                    <span wire:loading wire:target="assignEditor">Assigning...</span>
                </button>
                <button wire:click="closeAssignEditorModal" type="button" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 cursor-pointer">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══ DECLINE SUBMISSION MODAL ═══ --}}
    @if($showDeclineModal)
    <div class="fixed inset-0 bg-black/50 flex items-start justify-center pt-20 z-50" wire:click.self="closeDeclineModal">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md" @click.stop>
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Decline Submission</h3>
                <button wire:click="closeDeclineModal" type="button" class="text-gray-400 hover:text-gray-600 text-xl cursor-pointer">&times;</button>
            </div>
            <div class="p-5">
                <p class="text-sm text-gray-600 mb-3">Are you sure you want to decline this submission?</p>
                <textarea wire:model="declineReason" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded text-sm" placeholder="Reason for declining (optional)..."></textarea>
            </div>
            <div class="flex justify-end gap-2 px-5 py-4 border-t border-gray-200 bg-gray-50">
                <button wire:click="declineSubmission" type="button" class="px-4 py-2 text-sm font-medium bg-red-600 text-white rounded hover:bg-red-700 cursor-pointer">
                    <span wire:loading.remove wire:target="declineSubmission">Decline Submission</span>
                    <span wire:loading wire:target="declineSubmission">Processing...</span>
                </button>
                <button wire:click="closeDeclineModal" type="button" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 cursor-pointer">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══ Accept Paper Modal (LOA + Invoice) ═══ --}}
    @if($showAcceptModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Accept Paper — LOA & Tagihan</h3>
                <button wire:click="closeAcceptModal" type="button" class="text-gray-400 hover:text-gray-600 text-xl cursor-pointer">&times;</button>
            </div>
            <div class="p-5 space-y-4">
                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                    <p class="text-sm text-green-800 font-medium">Paper: {{ \Illuminate\Support\Str::limit($paper->title, 60) }}</p>
                    <p class="text-xs text-green-600 mt-1">Author: {{ $paper->user->name }}</p>
                </div>

                {{-- LOA Mode Toggle --}}
                @php $conf = $paper->conference; @endphp
                <div class="border border-gray-200 rounded-lg p-4">
                    <p class="text-sm font-semibold text-gray-700 mb-3">📜 Pilih Mode LOA</p>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-start gap-2 p-3 border-2 rounded-lg cursor-pointer transition"
                               :class="$wire.autoGenerateLoa ? '' : 'border-blue-500 bg-blue-50'">
                            <input type="radio" wire:model.live="autoGenerateLoa" :value="false" class="mt-0.5">
                            <div>
                                <p class="text-sm font-medium text-gray-800">Manual</p>
                                <p class="text-xs text-gray-500 mt-0.5">Input link LOA sendiri</p>
                            </div>
                        </label>
                        <label class="flex items-start gap-2 p-3 border-2 rounded-lg cursor-pointer transition"
                               :class="$wire.autoGenerateLoa ? 'border-green-500 bg-green-50' : ''">
                            <input type="radio" wire:model.live="autoGenerateLoa" :value="true" class="mt-0.5">
                            <div>
                                <p class="text-sm font-medium text-gray-800">Auto-Generate</p>
                                <p class="text-xs text-gray-500 mt-0.5">Sistem buat PDF otomatis</p>
                                <span class="text-xs text-green-600 font-medium">✨ QR Code & Nomor Unik</span>
                            </div>
                        </label>
                    </div>
                    @if($conf && $conf->loa_generation_mode === 'auto')
                        <p class="text-xs text-blue-600 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Mode default conference ini: Auto-Generate
                        </p>
                    @endif
                </div>

                {{-- LOA Link Input (only if manual) --}}
                @if(!$autoGenerateLoa)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link LOA (Letter of Acceptance) <span class="text-red-500">*</span></label>
                    <input type="url" wire:model="acceptLoaLink" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="https://drive.google.com/... atau URL LOA">
                    @error('acceptLoaLink') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-400 mt-1">Masukkan link file LOA (Google Drive, Dropbox, dll.)</p>
                </div>
                @else
                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <p class="text-sm font-medium text-green-800">LOA akan di-generate otomatis saat Anda konfirmasi</p>
                            <ul class="text-xs text-green-700 mt-1 space-y-0.5">
                                <li>• Nomor LOA unik (LOA/001/{{ $paper->conference->acronym ?? 'CONF' }}/{{ now()->year }})</li>
                                <li>• QR Code untuk verifikasi</li>
                                <li>• PDF tersimpan di server</li>
                                <li>• Author dapat download langsung</li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tagihan (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" wire:model="acceptInvoiceAmount" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="500000">
                    @error('acceptInvoiceAmount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Tagihan</label>
                    <input type="text" wire:model="acceptInvoiceDescription" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Info Rekening Bank --}}
                @php
                    $bankConf = $paper->conference;
                    $bankName = $bankConf->payment_bank_name ?? null;
                    $bankAccount = $bankConf->payment_bank_account ?? null;
                    $accountHolder = $bankConf->payment_account_holder ?? null;
                @endphp
                @if($bankName || $bankAccount)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <p class="text-xs font-bold text-blue-800 mb-2 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Info Rekening Pembayaran
                    </p>
                    <div class="space-y-1 text-xs">
                        @if($bankName)
                        <div class="flex justify-between"><span class="text-gray-500">Bank</span><span class="font-medium text-gray-800">{{ $bankName }}</span></div>
                        @endif
                        @if($bankAccount)
                        <div class="flex justify-between"><span class="text-gray-500">No. Rekening</span><span class="font-mono font-bold text-gray-800">{{ $bankAccount }}</span></div>
                        @endif
                        @if($accountHolder)
                        <div class="flex justify-between"><span class="text-gray-500">Atas Nama</span><span class="font-medium text-gray-800">{{ $accountHolder }}</span></div>
                        @endif
                    </div>
                    <p class="text-[10px] text-blue-600 mt-2 italic">Info ini akan ditampilkan ke author bersama tagihan.</p>
                </div>
                @endif

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-xs text-yellow-700">
                    <p class="font-medium mb-1">⚠️ Setelah dikonfirmasi:</p>
                    <ul class="list-disc pl-4 space-y-0.5">
                        <li>Status paper berubah ke <strong>Menunggu Pembayaran</strong></li>
                        <li>LOA & tagihan otomatis dikirim ke author</li>
                        <li>Author <strong>wajib</strong> upload bukti bayar</li>
                    </ul>
                </div>
            </div>
            <div class="flex justify-end gap-2 px-5 py-4 border-t border-gray-200 bg-gray-50">
                <button wire:click="confirmAccept" type="button" class="px-4 py-2 text-sm font-medium bg-green-600 text-white rounded hover:bg-green-700 cursor-pointer">
                    <span wire:loading.remove wire:target="confirmAccept">
                        @if($autoGenerateLoa)
                            ✨ Konfirmasi Accept & Generate LOA
                        @else
                            Konfirmasi Accept & Kirim LOA
                        @endif
                    </span>
                    <span wire:loading wire:target="confirmAccept">Processing...</span>
                </button>
                <button wire:click="closeAcceptModal" type="button" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 cursor-pointer">
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══ PUBLISH MODAL ═══ --}}
    @if($showPublishModal)
    <div class="fixed inset-0 bg-black/50 flex items-start justify-center pt-20 z-50" wire:click.self="closePublishModal">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md" @click.stop>
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Publikasi Paper</h3>
                <button wire:click="closePublishModal" type="button" class="text-gray-400 hover:text-gray-600 text-xl cursor-pointer">&times;</button>
            </div>
            <div class="p-5 space-y-4">
                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                    <p class="text-sm text-green-800 font-medium">Paper akan dipublikasikan:</p>
                    <p class="text-sm text-green-700 mt-1 italic">"{{ \Illuminate\Support\Str::limit($paper->title, 80) }}"</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link Artikel <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <input wire:model="publishArticleLink" type="url" placeholder="https://jurnal.example.com/artikel/..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-400 mt-1">Masukkan link ke artikel yang sudah dipublikasikan (jurnal, repository, dll).</p>
                    @error('publishArticleLink') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-xs text-amber-700">
                    <p class="font-medium mb-1">Perhatian:</p>
                    <ul class="list-disc pl-4 space-y-0.5">
                        <li>Status paper akan berubah ke <strong>Completed</strong></li>
                        <li>Author akan menerima notifikasi bahwa paper telah dipublikasikan</li>
                        <li>Link artikel bisa diubah/ditambah nanti</li>
                    </ul>
                </div>
            </div>
            <div class="flex justify-end gap-2 px-5 py-4 border-t border-gray-200 bg-gray-50">
                <button wire:click="markAsCompleted" type="button" class="px-4 py-2 text-sm font-medium bg-green-600 text-white rounded hover:bg-green-700 cursor-pointer">
                    <span wire:loading.remove wire:target="markAsCompleted">Publikasikan</span>
                    <span wire:loading wire:target="markAsCompleted">Processing...</span>
                </button>
                <button wire:click="closePublishModal" type="button" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 cursor-pointer">
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══ Add Discussion Modal ═══ --}}
    @if($showDiscussionModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Add Discussion</h3>
                <button wire:click="closeDiscussionModal" type="button" class="text-gray-400 hover:text-gray-600 text-xl cursor-pointer">&times;</button>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="discussionSubject" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Judul diskusi...">
                    @error('discussionSubject') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
                    <textarea wire:model="discussionMessage" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Tulis pesan..."></textarea>
                    @error('discussionMessage') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="flex justify-end gap-2 px-5 py-4 border-t border-gray-200 bg-gray-50">
                <button wire:click="createDiscussion" type="button" class="px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded hover:bg-blue-700 cursor-pointer">
                    <span wire:loading.remove wire:target="createDiscussion">Create Discussion</span>
                    <span wire:loading wire:target="createDiscussion">Creating...</span>
                </button>
                <button wire:click="closeDiscussionModal" type="button" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 cursor-pointer">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══ Activity Log Slide Panel ═══ --}}
    <div x-show="showActivityLog" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 w-80 bg-white shadow-xl border-l border-gray-200 z-50 overflow-y-auto" @click.away="showActivityLog = false">
        <div class="p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800">Activity Log</h3>
                <button type="button" @click="showActivityLog = false" class="text-gray-400 hover:text-gray-600 cursor-pointer">&times;</button>
            </div>
            <div class="space-y-3 text-xs">
                <div class="border-l-2 border-blue-500 pl-3 py-1">
                    <p class="font-medium text-gray-800">Submission created</p>
                    <p class="text-gray-500">{{ $paper->submitted_at?->format('M d, Y H:i') ?? $paper->created_at->format('M d, Y H:i') }}</p>
                </div>
                @if($paper->status !== 'submitted')
                <div class="border-l-2 border-indigo-500 pl-3 py-1">
                    <p class="font-medium text-gray-800">Status changed to {{ $paper->status_label }}</p>
                    <p class="text-gray-500">{{ $paper->updated_at->format('M d, Y H:i') }}</p>
                </div>
                @endif
                @foreach($paper->reviews as $review)
                <div class="border-l-2 border-purple-500 pl-3 py-1">
                    <p class="font-medium text-gray-800">Reviewer assigned: {{ $review->reviewer->name }}</p>
                    <p class="text-gray-500">{{ $review->created_at->format('M d, Y H:i') }}</p>
                </div>
                @if($review->status === 'completed')
                <div class="border-l-2 border-green-500 pl-3 py-1">
                    <p class="font-medium text-gray-800">Review completed by {{ $review->reviewer->name }}</p>
                    <p class="text-gray-500">{{ $review->reviewed_at?->format('M d, Y H:i') ?? '-' }}</p>
                </div>
                @endif
                @endforeach
                @if($paper->accepted_at)
                <div class="border-l-2 border-green-500 pl-3 py-1">
                    <p class="font-medium text-gray-800">Paper accepted</p>
                    <p class="text-gray-500">{{ $paper->accepted_at->format('M d, Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
