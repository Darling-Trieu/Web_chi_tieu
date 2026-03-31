<?php
require $_SERVER['DOCUMENT_ROOT'] . '/src/views/partials/application/AppHeader.php';
?>
        <div class="flex flex-col justify-center items-center min-h-screen px-4 py-8">
            <div class="max-w-lg w-full space-y-4">
                <div class="flex items-center gap-2.5 h-14 px-4 rounded-lg bg-card border border-border text-foreground text-xl font-bold tracking-tight shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 5c-1.5 0-2.8 1.4-3 2-3.5-1.5-11-.3-11 5 0 1.8 0 3 2 4.5V20h4v-2h3v2h4v-4c1-.5 1.7-1 2-2h2v-4h-2c0-1-.5-1.5-1-2V5z"/><path d="M2 9v1c0 1.1.9 2 2 2h1"/><path d="M16 11h.01"/></svg>
                    <span>Quản lý chi tiêu</span>
                </div>
                <form id="expenseForm" method="post" action="" class="flex flex-col gap-5 p-6 rounded-lg bg-card border border-border shadow-sm">
                    <div class="flex flex-col gap-1.5">
                        <label for="amount" class="text-sm font-medium leading-none text-foreground select-none tracking-wide">Số Tiền</label>
                        <input type="number" id="amount" name="amount" placeholder="Nhập số tiền..." required min="0" step="0.01"
                               class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground/65 outline-none transition-colors focus:border-ring focus:ring-2 focus:ring-ring/25 hover:border-input/80">
                        <div class="text-xs text-destructive" id="amountError"></div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label for="type" class="text-sm font-medium leading-none text-foreground select-none tracking-wide">Loại Giao Dịch</label>
                        <div class="relative w-full" data-select>
                            <button type="button" data-select-trigger
                                    class="flex items-center justify-between h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm text-muted-foreground cursor-pointer outline-none transition-colors hover:border-input/80 data-[state=open]:border-ring data-[state=open]:ring-2 data-[state=open]:ring-ring/25">
                                <span data-select-value>Chọn loại</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 opacity-50 transition-transform duration-200"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <select id="type" name="type" required class="sr-only" tabindex="-1" aria-hidden="true">
                                <option value="">Chọn loại</option>
                                <option value="Thu">Thu</option>
                                <option value="Chi">Chi</option>
                            </select>
                            <div class="custom-select-content absolute top-[calc(100%+4px)] left-0 w-full z-50 hidden flex-col rounded-md border border-border bg-card p-1 shadow-md" data-state="closed" data-select-content>
                                <div class="flex items-center gap-2 rounded-sm px-2 py-1.5 text-sm text-foreground cursor-pointer select-none transition-colors hover:bg-accent hover:text-accent-foreground" data-value="" data-select-item>Chọn loại</div>
                                <div class="flex items-center gap-2 rounded-sm px-2 py-1.5 text-sm text-foreground cursor-pointer select-none transition-colors hover:bg-accent hover:text-accent-foreground" data-value="Thu" data-select-item>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 opacity-70"><path d="M12 19V5"/><path d="m5 12 7-7 7 7"/></svg>
                                    Thu
                                </div>
                                <div class="flex items-center gap-2 rounded-sm px-2 py-1.5 text-sm text-foreground cursor-pointer select-none transition-colors hover:bg-accent hover:text-accent-foreground" data-value="Chi" data-select-item>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 opacity-70"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg>
                                    Chi
                                </div>
                            </div>
                        </div>
                        <div class="text-xs text-destructive" id="typeError"></div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label for="category" class="text-sm font-medium leading-none text-foreground select-none tracking-wide">Danh Mục</label>
                        <div class="relative w-full" data-select>
                            <button type="button" data-select-trigger
                                    class="flex items-center justify-between h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm text-muted-foreground cursor-pointer outline-none transition-colors hover:border-input/80 data-[state=open]:border-ring data-[state=open]:ring-2 data-[state=open]:ring-ring/25">
                                <span data-select-value>Chọn danh mục</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 opacity-50 transition-transform duration-200"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <select id="category" name="category" required class="sr-only" tabindex="-1" aria-hidden="true">
                                <option value="">Chọn danh mục</option>
                                <option value="Ăn uống">Ăn uống</option>
                                <option value="Di chuyển">Di chuyển</option>
                                <option value="Mua sắm">Mua sắm</option>
                                <option value="Giải trí">Giải trí</option>
                                <option value="Khác">Khác</option>
                            </select>
                            <div class="custom-select-content absolute top-[calc(100%+4px)] left-0 w-full z-50 hidden flex-col rounded-md border border-border bg-card p-1 shadow-md" data-state="closed" data-select-content>
                                <div class="flex items-center gap-2 rounded-sm px-2 py-1.5 text-sm text-foreground cursor-pointer select-none transition-colors hover:bg-accent hover:text-accent-foreground" data-value="" data-select-item>Chọn danh mục</div>
                                <div class="flex items-center gap-2 rounded-sm px-2 py-1.5 text-sm text-foreground cursor-pointer select-none transition-colors hover:bg-accent hover:text-accent-foreground" data-value="Ăn uống" data-select-item>🍜 Ăn uống</div>
                                <div class="flex items-center gap-2 rounded-sm px-2 py-1.5 text-sm text-foreground cursor-pointer select-none transition-colors hover:bg-accent hover:text-accent-foreground" data-value="Di chuyển" data-select-item>🚗 Di chuyển</div>
                                <div class="flex items-center gap-2 rounded-sm px-2 py-1.5 text-sm text-foreground cursor-pointer select-none transition-colors hover:bg-accent hover:text-accent-foreground" data-value="Mua sắm" data-select-item>🛍️ Mua sắm</div>
                                <div class="flex items-center gap-2 rounded-sm px-2 py-1.5 text-sm text-foreground cursor-pointer select-none transition-colors hover:bg-accent hover:text-accent-foreground" data-value="Giải trí" data-select-item>🎮 Giải trí</div>
                                <div class="flex items-center gap-2 rounded-sm px-2 py-1.5 text-sm text-foreground cursor-pointer select-none transition-colors hover:bg-accent hover:text-accent-foreground" data-value="Khác" data-select-item>📦 Khác</div>
                            </div>
                        </div>
                        <div class="text-xs text-destructive" id="categoryError"></div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label for="date" class="text-sm font-medium leading-none text-foreground select-none tracking-wide">Ngày Giao Dịch</label>
                        <input type="date" id="date" name="date" required
                               class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm text-foreground outline-none transition-colors focus:border-ring focus:ring-2 focus:ring-ring/25 hover:border-input/80">
                        <div class="text-xs text-destructive" id="dateError"></div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label for="note" class="text-sm font-medium leading-none text-foreground select-none tracking-wide">
                            Ghi Chú <span class="font-normal text-muted-foreground text-xs">(Tùy chọn)</span>
                        </label>
                        <textarea id="note" name="note" rows="3" placeholder="Nhập ghi chú..."
                                  class="flex w-full min-h-20 rounded-md border border-input bg-transparent px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground/65 outline-none transition-colors focus:border-ring focus:ring-2 focus:ring-ring/25 hover:border-input/80 resize-y"></textarea>
                    </div>
                    <button type="submit" id="submitBtn"
                            class="inline-flex items-center justify-center gap-2 h-10 px-5 rounded-md bg-primary text-primary-foreground text-sm font-medium whitespace-nowrap cursor-pointer transition-all duration-150 outline-none select-none hover:bg-primary/90 focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background active:scale-[0.98] disabled:pointer-events-none disabled:opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        Lưu Giao Dịch
                    </button>
                </form>
            </div>
        </div>
        <script>
        document.querySelectorAll('[data-select]').forEach(wrapper => {
            const trigger = wrapper.querySelector('[data-select-trigger]');
            const content = wrapper.querySelector('[data-select-content]');
            const valueSpan = wrapper.querySelector('[data-select-value]');
            const nativeSelect = wrapper.querySelector('select');
            const items = content.querySelectorAll('[data-select-item]');
            const chevron = trigger.querySelector('svg');

            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                document.querySelectorAll('[data-select-content]').forEach(c => {
                    if (c !== content) {
                        c.setAttribute('data-state', 'closed');
                        c.classList.add('hidden');
                        c.closest('[data-select]').querySelector('[data-select-trigger]').removeAttribute('data-state');
                        c.closest('[data-select]').querySelector('[data-select-trigger] svg').style.transform = '';
                    }
                });
                const isOpen = content.getAttribute('data-state') === 'open';
                content.setAttribute('data-state', isOpen ? 'closed' : 'open');
                content.classList.toggle('hidden', isOpen);
                trigger.setAttribute('data-state', isOpen ? 'closed' : 'open');
                chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
            });

            items.forEach(item => {
                item.addEventListener('click', () => {
                    const value = item.getAttribute('data-value');
                    nativeSelect.value = value;
                    nativeSelect.dispatchEvent(new Event('change', { bubbles: true }));
                    valueSpan.textContent = item.textContent.trim();
                    valueSpan.classList.toggle('text-foreground', !!value);
                    valueSpan.classList.toggle('text-muted-foreground', !value);
                    items.forEach(i => i.classList.remove('bg-accent', 'font-medium'));
                    if (value) item.classList.add('bg-accent', 'font-medium');
                    content.setAttribute('data-state', 'closed');
                    content.classList.add('hidden');
                    trigger.removeAttribute('data-state');
                    chevron.style.transform = '';
                });
            });

            document.addEventListener('click', (e) => {
                if (!wrapper.contains(e.target)) {
                    content.setAttribute('data-state', 'closed');
                    content.classList.add('hidden');
                    trigger.removeAttribute('data-state');
                    chevron.style.transform = '';
                }
            });
        });
        </script>
<?php
require $_SERVER['DOCUMENT_ROOT'] . '/src/views/partials/application/AppFooter.php';
?>