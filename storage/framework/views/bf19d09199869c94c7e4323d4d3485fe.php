<div>
    <div class="max-w-7xl mx-auto px-4 py-8">
        
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-500 flex items-center justify-center text-white text-xl font-bold">W</div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">WhatsApp Notifikasi</h2>
                    <p class="text-sm text-gray-500">Integrasi Fonnte / Wablas untuk reminder & update status otomatis</p>
                </div>
            </div>
            <div class="flex gap-2">
                <button wire:click="$set('showSendModal', true)" class="inline-flex items-center gap-2 px-4 py-2 border border-green-500 text-green-600 rounded-xl text-sm font-medium hover:bg-green-50 transition">
                    💬 Kirim Manual
                </button>
                <button wire:click="openCreate" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-xl text-sm font-semibold hover:bg-green-700 transition">
                    + Tambah Konfigurasi
                </button>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">✅ <?php echo e(session('success')); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">❌ <?php echo e(session('error')); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="flex gap-1 mb-6 border-b border-gray-200">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['settings' => 'Konfigurasi', 'templates' => 'Template Pesan', 'reminder' => 'Kirim Reminder', 'logs' => 'Log Pengiriman']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <button wire:click="$set('activeTab', '<?php echo e($tab); ?>')"
                class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition <?php echo e($activeTab === $tab ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700'); ?>">
                <?php echo e($label); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tab === 'logs' && $logStats['today'] > 0): ?>
                <span class="ml-1 inline-flex px-1.5 py-0.5 text-[10px] font-bold bg-green-100 text-green-700 rounded-full"><?php echo e($logStats['today']); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </button>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'settings'): ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeSetting): ?>
        <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-5 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white text-xs font-bold">✓</div>
                <div>
                    <p class="text-sm font-semibold text-green-800">Aktif: <?php echo e($activeSetting->name); ?>

                        <span class="ml-2 px-2 py-0.5 text-[10px] font-bold uppercase rounded-full bg-green-200 text-green-800"><?php echo e($activeSetting->provider_label); ?></span>
                    </p>
                    <p class="text-xs text-green-600">Sender: <?php echo e($activeSetting->sender_number ?? 'tidak dikonfigurasi'); ?></p>
                </div>
            </div>
            <button wire:click="testSend(<?php echo e($activeSetting->id); ?>)" class="px-3 py-1.5 bg-green-600 text-white text-xs font-semibold rounded-lg hover:bg-green-700 transition">
                🔔 Test Kirim
            </button>
        </div>
        <?php else: ?>
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4 mb-5 text-sm text-yellow-700">
            ⚠️ Belum ada konfigurasi aktif. Tambah konfigurasi dan aktifkan agar notifikasi WhatsApp berfungsi.
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Nama</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Provider</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden md:table-cell">API Key</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden lg:table-cell">Nomor Sender</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">Status</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <tr class="hover:bg-gray-50 <?php echo e($s->is_active ? 'bg-green-50/30' : ''); ?>">
                        <td class="px-4 py-3 font-medium text-gray-800"><?php echo e($s->name); ?></td>
                        <td class="px-4 py-3">
                            <span class="inline-flex px-2 py-0.5 text-xs rounded-full
                                <?php echo e($s->provider === 'fonnte' ? 'bg-blue-100 text-blue-700' : ($s->provider === 'wablas' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700')); ?>">
                                <?php echo e($s->provider_label); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell text-gray-500 font-mono text-xs">
                            <?php echo e($s->api_key ? substr($s->api_key, 0, 8) . '••••••••' : '-'); ?>

                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell text-gray-600 text-xs"><?php echo e($s->sender_number ?? '-'); ?></td>
                        <td class="px-4 py-3 text-center">
                            <button wire:click="toggleActive(<?php echo e($s->id); ?>)" title="<?php echo e($s->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full cursor-pointer
                                    <?php echo e($s->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'); ?>">
                                    <?php echo e($s->is_active ? '● Aktif' : '○ Nonaktif'); ?>

                                </span>
                            </button>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <button wire:click="testSend(<?php echo e($s->id); ?>)" class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition font-medium">Test</button>
                                <button wire:click="openEdit(<?php echo e($s->id); ?>)" class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition font-medium">Edit</button>
                                <button wire:click="deleteSetting(<?php echo e($s->id); ?>)" wire:confirm="Hapus konfigurasi ini?" class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition font-medium">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr><td colspan="6" class="text-center py-10 text-gray-400">Belum ada konfigurasi. Klik "+ Tambah Konfigurasi".</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-white border border-gray-200 rounded-2xl p-4 text-center">
                <p class="text-2xl font-bold text-gray-800"><?php echo e($logStats['total']); ?></p>
                <p class="text-xs text-gray-500 mt-1">Total Terkirim</p>
            </div>
            <div class="bg-white border border-green-200 rounded-2xl p-4 text-center">
                <p class="text-2xl font-bold text-green-600"><?php echo e($logStats['sent']); ?></p>
                <p class="text-xs text-gray-500 mt-1">Berhasil</p>
            </div>
            <div class="bg-white border border-red-200 rounded-2xl p-4 text-center">
                <p class="text-2xl font-bold text-red-500"><?php echo e($logStats['failed']); ?></p>
                <p class="text-xs text-gray-500 mt-1">Gagal</p>
            </div>
            <div class="bg-white border border-blue-200 rounded-2xl p-4 text-center">
                <p class="text-2xl font-bold text-blue-600"><?php echo e($logStats['today']); ?></p>
                <p class="text-xs text-gray-500 mt-1">Hari Ini</p>
            </div>
        </div>

        
        <?php elseif($activeTab === 'templates'): ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$activeSetting): ?>
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4 text-sm text-yellow-700 mb-4">
            ⚠️ Aktifkan konfigurasi terlebih dahulu untuk mengedit template.
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = \App\Models\WhatsappSetting::TEMPLATES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <?php
                $currentTpl = $activeSetting?->$key ?? \App\Models\WhatsappSetting::DEFAULT_TEMPLATES[$key] ?? '';
                $isCustom = $activeSetting && $activeSetting->$key !== null;
            ?>
            <div class="bg-white rounded-2xl border border-gray-200 p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-semibold text-gray-800"><?php echo e($label); ?></h3>
                    <div class="flex items-center gap-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isCustom): ?>
                        <span class="text-[10px] px-1.5 py-0.5 bg-blue-100 text-blue-700 rounded-full">Custom</span>
                        <button wire:click="resetTemplate('<?php echo e($key); ?>')" class="text-[10px] text-gray-400 hover:text-orange-500 transition">Reset</button>
                        <?php else: ?>
                        <span class="text-[10px] px-1.5 py-0.5 bg-gray-100 text-gray-500 rounded-full">Default</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <button wire:click="openTemplate('<?php echo e($key); ?>')" class="px-2 py-0.5 text-xs bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition font-medium">Edit</button>
                    </div>
                </div>
                <p class="text-xs text-gray-500 whitespace-pre-line line-clamp-4 font-mono bg-gray-50 rounded-lg px-3 py-2 leading-relaxed"><?php echo e($currentTpl); ?></p>
                <div class="mt-2 flex flex-wrap gap-1">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->getTemplateVars($key); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $var): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <code class="text-[10px] bg-green-50 text-green-700 px-1.5 py-0.5 rounded border border-green-100">&#123;<?php echo e($var); ?>&#125;</code>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

        
        <?php elseif($activeTab === 'reminder'): ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <h3 class="font-semibold text-gray-800 mb-1">Reminder Pembayaran</h3>
                <p class="text-xs text-gray-500 mb-4">Kirim reminder ke author yang belum bayar, targetkan berdasarkan status.</p>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Konferensi</label>
                        <select wire:model="reminderConferenceId" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                            <option value="0">Semua Konferensi</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $conferences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <option value="<?php echo e($conf->id); ?>"><?php echo e($conf->name); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Status Paper Target</label>
                        <select wire:model="reminderStatus" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                            <option value="payment_pending">Menunggu Pembayaran</option>
                            <option value="accepted">Accepted (belum bayar)</option>
                        </select>
                    </div>
                    <?php
                        $targetCount = \App\Models\Paper::where('status', $reminderStatus)
                            ->when($reminderConferenceId, fn($q) => $q->where('conference_id', $reminderConferenceId))
                            ->whereHas('user', fn($q) => $q->whereNotNull('phone'))
                            ->count();
                    ?>
                    <div class="p-3 bg-green-50 rounded-xl border border-green-200 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Target dengan nomor HP</p>
                            <p class="text-xl font-bold text-green-700"><?php echo e($targetCount); ?> orang</p>
                        </div>
                        <svg class="w-8 h-8 text-green-300" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </div>
                    <button wire:click="sendPaymentReminders" wire:confirm="Kirim reminder ke <?php echo e($targetCount); ?> orang? Proses ini akan memakan beberapa waktu."
                        class="w-full px-4 py-2.5 bg-green-600 text-white rounded-xl text-sm font-semibold hover:bg-green-700 transition disabled:opacity-50"
                        <?php if($targetCount === 0 || !$activeSetting): echo 'disabled'; endif; ?>>
                        <span wire:loading.remove wire:target="sendPaymentReminders">📤 Kirim Reminder (<?php echo e($targetCount); ?>)</span>
                        <span wire:loading wire:target="sendPaymentReminders">Mengirim... harap tunggu</span>
                    </button>
                </div>
            </div>

            
            <div class="bg-gray-50 rounded-2xl border border-gray-200 p-5">
                <h3 class="font-semibold text-gray-800 mb-3">📋 Petunjuk</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex gap-2"><span class="text-green-500 font-bold">1.</span> Pastikan pengguna memiliki nomor HP yang valid di profil mereka.</li>
                    <li class="flex gap-2"><span class="text-green-500 font-bold">2.</span> Nomor HP otomatis dinormalisasi ke format internasional (628xxx).</li>
                    <li class="flex gap-2"><span class="text-green-500 font-bold">3.</span> Delay 0.5 detik antar pesan untuk menghindari rate limit provider.</li>
                    <li class="flex gap-2"><span class="text-green-500 font-bold">4.</span> Semua pengiriman tercatat di tab "Log Pengiriman".</li>
                    <li class="flex gap-2"><span class="text-green-500 font-bold">5.</span> Status paper otomatis memicu notifikasi WA jika fitur diaktifkan.</li>
                </ul>
                <div class="mt-4 p-3 bg-blue-50 rounded-xl border border-blue-200">
                    <p class="text-xs font-semibold text-blue-700 mb-1">Endpoint API:</p>
                    <p class="text-xs text-blue-600 font-mono">Fonnte: api.fonnte.com/send</p>
                    <p class="text-xs text-blue-600 font-mono">Wablas: solo.wablas.com/api/send-message</p>
                </div>
            </div>
        </div>

        
        <?php elseif($activeTab === 'logs'): ?>

        <div class="flex flex-wrap gap-3 mb-4 items-center justify-between">
            <div class="flex flex-wrap gap-2 flex-1">
                <input wire:model.live.debounce.300ms="logSearch" type="text" placeholder="Cari nomor / nama..." class="px-3 py-2 border border-gray-300 rounded-xl text-sm max-w-xs">
                <select wire:model.live="logType" class="px-3 py-2 border border-gray-300 rounded-xl text-sm">
                    <option value="">Semua Tipe</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = \App\Models\WhatsappLog::TYPES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <option value="<?php echo e($k); ?>"><?php echo e($v); ?></option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
                <select wire:model.live="logStatus" class="px-3 py-2 border border-gray-300 rounded-xl text-sm">
                    <option value="">Semua Status</option>
                    <option value="sent">Terkirim</option>
                    <option value="failed">Gagal</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            <button wire:click="clearLogs" wire:confirm="Hapus log lebih dari 30 hari?" class="px-3 py-2 text-xs border border-red-200 text-red-600 rounded-xl hover:bg-red-50 transition">
                🗑 Bersihkan Log Lama
            </button>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Tujuan</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden md:table-cell">Tipe</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden lg:table-cell">Pesan</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">Status</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden md:table-cell">Waktu</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800 text-xs"><?php echo e($log->recipient_name ?? $log->to); ?></p>
                            <p class="text-[11px] text-gray-400 font-mono"><?php echo e($log->to); ?></p>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            <span class="inline-flex px-2 py-0.5 text-[10px] rounded-full bg-gray-100 text-gray-600 font-medium"><?php echo e($log->type_label); ?></span>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell text-xs text-gray-500 max-w-[200px] truncate"><?php echo e($log->message); ?></td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full
                                <?php echo e($log->status === 'sent' ? 'bg-green-100 text-green-700' :
                                   ($log->status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700')); ?>">
                                <?php echo e(ucfirst($log->status)); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell text-xs text-gray-400"><?php echo e($log->created_at->format('d/m/y H:i')); ?></td>
                        <td class="px-4 py-3 text-center">
                            <button wire:click="deleteLog(<?php echo e($log->id); ?>)" class="text-red-400 hover:text-red-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr><td colspan="6" class="text-center py-10 text-gray-400">Belum ada log pengiriman WA.</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logs->hasPages()): ?>
            <div class="px-4 py-3 border-t border-gray-200"><?php echo e($logs->links()); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showSettingModal): ?>
    <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800"><?php echo e($editId ? 'Edit' : 'Tambah'); ?> Konfigurasi WhatsApp</h3>
                <button wire:click="$set('showSettingModal', false)" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Profil</label>
                        <input wire:model="settingName" type="text" placeholder="e.g. Fonnte Utama" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['settingName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Provider</label>
                        <select wire:model.live="provider" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = \App\Models\WhatsappSetting::PROVIDERS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <option value="<?php echo e($k); ?>"><?php echo e($v); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        API Key / Token
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($provider === 'fonnte'): ?> <span class="text-gray-400 font-normal">— dari dashboard fonnte.com</span>
                        <?php elseif($provider === 'wablas'): ?> <span class="text-gray-400 font-normal">— dari dashboard wablas.com</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </label>
                    <input wire:model="apiKey" type="text" placeholder="Masukkan API Key..." class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm font-mono">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['apiKey'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($provider === 'wablas' || $provider === 'custom'): ?>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        API URL
                        <span class="text-gray-400 font-normal"><?php echo e($provider === 'wablas' ? 'e.g. https://solo.wablas.com/api/send-message' : 'URL endpoint custom'); ?></span>
                    </label>
                    <input wire:model="apiUrl" type="url" placeholder="https://..." class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm font-mono">
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($provider === 'wablas'): ?>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Device ID <span class="text-gray-400 font-normal">(opsional, untuk multi-device)</span></label>
                    <input wire:model="deviceId" type="text" placeholder="Device ID..." class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nomor Pengirim <span class="text-gray-400 font-normal">(untuk info)</span></label>
                        <input wire:model="senderNumber" type="text" placeholder="628xxx" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nomor Test</label>
                        <input wire:model="testNumber" type="text" placeholder="628xxx" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                    </div>
                </div>

                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <p class="text-xs font-semibold text-gray-700 mb-3">Aktifkan Notifikasi</p>
                    <div class="grid grid-cols-1 gap-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                            'notifyPaymentReminder' => 'Reminder Pembayaran',
                            'notifyPaymentVerified' => 'Pembayaran Terverifikasi',
                            'notifyPaperStatus'     => 'Perubahan Status Paper',
                            'notifyReviewAssigned'  => 'Assigned ke Reviewer',
                            'notifyAbstractStatus'  => 'Status Abstrak',
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prop => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input wire:model="<?php echo e($prop); ?>" type="checkbox" class="w-4 h-4 text-green-600 rounded">
                            <?php echo e($label); ?>

                        </label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>

                <label class="flex items-center gap-3 p-3 bg-green-50 rounded-xl border border-green-200 cursor-pointer">
                    <input wire:model="isActive" type="checkbox" class="w-4 h-4 text-green-600 rounded">
                    <div>
                        <p class="text-sm font-semibold text-green-800">Jadikan Active</p>
                        <p class="text-xs text-green-600">Hanya satu konfigurasi yang bisa aktif sekaligus</p>
                    </div>
                </label>
            </div>
            <div class="flex justify-end gap-3 p-5 border-t border-gray-200">
                <button wire:click="$set('showSettingModal', false)" class="px-4 py-2 border border-gray-300 rounded-xl text-sm">Batal</button>
                <button wire:click="saveSetting" class="px-5 py-2 bg-green-600 text-white rounded-xl text-sm font-semibold hover:bg-green-700 transition">Simpan</button>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showTemplateModal): ?>
    <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-2xl shadow-2xl">
            <div class="flex items-center justify-between p-5 border-b border-gray-200">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Edit Template</h3>
                    <p class="text-xs text-gray-500"><?php echo e(\App\Models\WhatsappSetting::TEMPLATES[$editingTemplateKey] ?? $editingTemplateKey); ?></p>
                </div>
                <button wire:click="$set('showTemplateModal', false)" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="p-5">
                <div class="mb-3 flex flex-wrap gap-1">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->getTemplateVars($editingTemplateKey); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $var): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <code class="text-[11px] bg-green-50 text-green-700 px-2 py-0.5 rounded border border-green-100 cursor-pointer"
                        onclick="insertTplVar('<?php echo e($var); ?>')">
                        &#123;<?php echo e($var); ?>&#125;
                    </code>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
                <script>
                function insertTplVar(v) {
                    var ta = document.getElementById('tplTextarea');
                    var pos = ta.selectionStart || 0;
                    ta.value = ta.value.slice(0, pos) + '{' + v + '}' + ta.value.slice(pos);
                    ta.focus();
                    ta.selectionStart = ta.selectionEnd = pos + v.length + 2;
                }
                </script>
                <textarea id="tplTextarea" wire:model="editingTemplateValue" rows="10"
                    class="w-full border border-gray-300 rounded-xl p-3 text-sm font-mono resize-y"></textarea>
                <p class="text-xs text-gray-400 mt-1">Mendukung format *bold* dan _italic_ WhatsApp</p>
            </div>
            <div class="flex justify-end gap-3 p-5 border-t border-gray-200">
                <button wire:click="$set('showTemplateModal', false)" class="px-4 py-2 border border-gray-300 rounded-xl text-sm">Batal</button>
                <button wire:click="saveTemplate" class="px-5 py-2 bg-green-600 text-white rounded-xl text-sm font-semibold hover:bg-green-700 transition">Simpan Template</button>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showSendModal): ?>
    <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl">
            <div class="flex items-center justify-between p-5 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Kirim Pesan Manual</h3>
                <button wire:click="$set('showSendModal', false)" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Penerima <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <input wire:model="sendToName" type="text" placeholder="Nama penerima..." class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nomor WhatsApp</label>
                    <input wire:model="sendToNumber" type="text" placeholder="628xxx atau 08xxx" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm font-mono">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['sendToNumber'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <p class="text-xs text-gray-400 mt-1">Format 08xxx akan otomatis dikonversi ke 628xxx</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Pesan</label>
                    <textarea wire:model="sendMessage" rows="5" placeholder="Tulis pesan..." class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm resize-none"></textarea>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['sendMessage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <div class="flex justify-end gap-3 p-5 border-t border-gray-200">
                <button wire:click="$set('showSendModal', false)" class="px-4 py-2 border border-gray-300 rounded-xl text-sm">Batal</button>
                <button wire:click="sendManual" class="px-5 py-2 bg-green-600 text-white rounded-xl text-sm font-semibold hover:bg-green-700 transition">
                    <span wire:loading.remove>📤 Kirim</span>
                    <span wire:loading>Mengirim...</span>
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH D:\LPKD-APJI\PROSIDING\resources\views/livewire/admin/whatsapp-manager.blade.php ENDPATH**/ ?>