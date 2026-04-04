<?php require $_SERVER['DOCUMENT_ROOT'] . '/src/views/partials/application/AppHeader.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="fixed top-0 left-0 w-full h-12 bg-gradient-to-r from-emerald-500 to-blue-500 text-white flex items-center px-6 z-50 justify-center text-xl font-bold">
    Quản lý chi tiêu
</div>

<div class="fixed top-12 left-0 w-full h-16 bg-white/80 backdrop-blur border-b shadow-sm flex items-center justify-between px-6 z-40">
    <div class="text-2xl font-bold text-emerald-600">HIENLTH</div>
    <div class="flex items-center gap-6">
        <div class="text-right">
            <p class="text-xs text-gray-400">Số dư hiện tại</p>
            <p class="font-bold text-lg <?= $balance >= 0 ? 'text-green-600' : 'text-red-600' ?>"><?= number_format($balance) ?>đ</p>
        </div>
        <button id="openExpenseForm" class="bg-emerald-500 text-white px-4 py-2 rounded-lg font-bold hover:bg-emerald-600 shadow-md transition-all">+ Giao dịch</button>
    </div>
</div>

<div class="flex pt-28">
    <div class="fixed left-0 w-64 h-full bg-white border-r p-4 space-y-2">
        <a href="?page=home" class="block p-3 rounded-xl <?= $page=='home'?'bg-emerald-50 text-emerald-600 font-bold':'' ?>">🏠 Trang chủ</a>
        <a href="?page=history" class="block p-3 rounded-xl <?= $page=='history'?'bg-emerald-50 text-emerald-600 font-bold':'' ?>">📜 Lịch sử giao dịch</a>
        <hr class="my-4">
        <a href="?action=export" class="block p-3 rounded-xl hover:bg-gray-100 text-blue-600 font-medium italic">📥 Xuất file Excel (CSV)</a>
    </div>

    <div class="ml-64 flex-1 p-8">
        <?php if ($page === 'home'): ?>
            <div class="max-w-5xl mx-auto space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-2xl border shadow-sm">
                        <h3 class="font-bold text-gray-700 mb-4">Hạn mức chi tiêu tháng này</h3>
                        <div class="flex items-end justify-between mb-2">
                            <span class="text-2xl font-bold text-gray-800"><?= number_format($budget) ?>đ</span>
                            <form method="POST" class="flex gap-2">
                                <input type="number" name="budget_amount" placeholder="Đổi hạn mức" class="border rounded px-2 py-1 text-sm w-28 outline-none focus:border-emerald-500">
                                <button name="set_budget" class="bg-gray-100 px-3 py-1 rounded text-sm hover:bg-emerald-500 hover:text-white transition-all">Lưu</button>
                            </form>
                        </div>
                        <?php 
                            $tongChi = 0;
                            foreach($transactions as $t) if($t['type']=='Chi') $tongChi += $t['amount'];
                            $percent = ($budget > 0) ? ($tongChi / $budget) * 100 : 0;
                            $barColor = ($percent > 100) ? 'bg-red-500' : 'bg-emerald-500';
                        ?>
                        <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden">
                            <div class="h-full <?= $barColor ?> transition-all" style="width: <?= min($percent, 100) ?>%"></div>
                        </div>
                        <p class="text-xs mt-2 <?= $percent > 100 ? 'text-red-500 font-bold' : 'text-gray-400' ?>">
                            Đã chi: <?= number_format($tongChi) ?>đ (<?= round($percent, 1) ?>%)
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border shadow-sm flex flex-col items-center">
                        <h3 class="font-bold text-gray-700 mb-4 w-full text-left">Phân bổ chi tiêu</h3>
                        <div class="w-full h-48">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="p-10 bg-emerald-50 rounded-3xl border border-emerald-100 text-center">
                    <h2 class="text-2xl font-bold text-emerald-800">Chào HIENLTH!</h2>
                    <p class="text-emerald-600 opacity-75">Hôm nay là <?= date('d/m/Y') ?>. Chúc bạn một ngày chi tiêu hợp lý!</p>
                </div>
            </div>

        <?php else: ?>
            <div class="max-w-4xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Danh sách giao dịch</h2>
                    <a href="?action=export" class="text-sm bg-blue-50 text-blue-600 px-3 py-1 rounded-lg border border-blue-200">⬇ Tải file CSV</a>
                </div>
                <div class="bg-white rounded-2xl border shadow-sm divide-y">
                    <?php if(empty($transactions)): ?> <p class="p-10 text-center text-gray-400">Chưa có giao dịch.</p> <?php endif; ?>
                    <?php foreach (array_reverse($transactions) as $t): ?>
                        <div class="p-4 flex justify-between items-center hover:bg-gray-50">
                            <div>
                                <p class="font-bold text-gray-700"><?= htmlspecialchars($t['category']) ?></p>
                                <p class="text-xs text-gray-400"><?= $t['date'] ?> • <?= htmlspecialchars($t['note']) ?></p>
                            </div>
                            <p class="font-bold <?= $t['type']=='Thu'?'text-emerald-500':'text-red-500' ?>">
                                <?= ($t['type']=='Thu'?'+':'-') . number_format($t['amount']) ?>đ
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div id="expenseModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex justify-center items-center z-[100] p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
        <form method="POST" class="p-6 space-y-4">
            <div class="flex justify-between items-center border-b pb-4">
                <span class="text-xl font-bold">Thêm Giao Dịch</span>
                <button type="button" id="closeExpenseForm" class="text-gray-400 hover:text-black">✕</button>
            </div>
            <input type="number" name="amount" placeholder="0 VND" required class="w-full text-3xl font-bold text-emerald-600 border-none focus:ring-0 p-0 text-center">
            <div class="grid grid-cols-2 gap-4">
                <select name="type" id="typeSelect" class="border p-2 rounded-xl outline-none">
                    <option value="Chi">Khoản Chi</option>
                    <option value="Thu">Khoản Thu</option>
                </select>
                <select name="category" id="categoryBox" class="border p-2 rounded-xl outline-none">
                    <option>Ăn uống</option><option>Học tập</option><option>Giải trí</option><option>Mua sắm</option><option>Di chuyển</option>
                </select>
            </div>
            <input type="date" name="date" value="<?= date('Y-m-d') ?>" class="w-full border p-2 rounded-xl">
            <textarea name="note" placeholder="Ghi chú thêm..." class="w-full border p-2 rounded-xl"></textarea>
            <button type="submit" class="w-full py-4 bg-emerald-500 text-white rounded-xl font-bold hover:bg-emerald-600 shadow-lg">LƯU THÔNG TIN</button>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById("expenseModal");
    document.getElementById("openExpenseForm").onclick = () => modal.classList.remove("hidden");
    document.getElementById("closeExpenseForm").onclick = () => modal.classList.add("hidden");

    <?php if ($page === 'home'): ?>
        <?php
            $cats = ['Ăn uống', 'Học tập', 'Giải trí', 'Mua sắm', 'Di chuyển'];
            $vals = [0,0,0,0,0];
            foreach($transactions as $t) {
                if($t['type']=='Chi') {
                    $idx = array_search($t['category'], $cats);
                    if($idx !== false) $vals[$idx] += $t['amount'];
                }
            }
        ?>
        const ctx = document.getElementById('pieChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?= json_encode($cats) ?>,
                datasets: [{
                    data: <?= json_encode($vals) ?>,
                    backgroundColor: ['#10b981','#3b82f6','#f59e0b','#ef4444','#8b5cf6'],
                    borderWidth: 0
                }]
            },
            options: { maintainAspectRatio: false, plugins: { legend: { position: 'right' } } }
        });
    <?php endif; ?>
</script>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/src/views/partials/application/AppFooter.php'; ?>