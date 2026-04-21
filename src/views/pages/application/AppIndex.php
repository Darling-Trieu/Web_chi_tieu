<?php require $_SERVER['DOCUMENT_ROOT'] . '/src/views/partials/application/AppHeader.php';
?>
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
        <a href="?page=chart" class="block p-3 rounded-xl <?= $page=='chart'?'bg-emerald-50 text-emerald-600 font-bold':'' ?>">
            📊 Biểu đồ tháng
        </a>
        <hr class="my-4">
        <a href="?page=export_preview" class="block p-3 rounded-xl <?= $page=='export_preview'?'bg-blue-50 text-blue-600 font-bold':'hover:bg-gray-100 text-blue-600 font-medium italic' ?>">📥 Xuất file Excel (CSV)</a>
    </div>

    <div class="ml-64 flex-1 p-8">

<?php if ($page === 'home'): ?>

    <div class="max-w-6xl mx-auto space-y-6">

        <!-- GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- BUDGET -->
            <div class="bg-white p-6 rounded-3xl border shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-600">Hạn mức chi tiêu</h3>
                    <form method="POST" class="flex gap-2">
                        <input type="number" name="budget_amount"
                            placeholder="Đổi tổng hạn mức"
                            class="border rounded-lg px-3 py-1 text-sm w-36 focus:border-emerald-500 outline-none">
                        <button name="set_budget"
                            class="bg-gray-100 px-3 py-1 rounded-lg text-sm hover:bg-emerald-500 hover:text-white transition">
                            Lưu
                        </button>
                    </form>
                </div>

                <?php 
                    $daysInMonth = date('t');
                    $budgetDaily = $budget / $daysInMonth;
                    $budgetWeekly = $budgetDaily * 7;

                    $tongChiThang = 0;
                    $tongChiTuan = 0;
                    $tongChiNgay = 0;

                    $currentMonth = date('Y-m');
                    $currentWeek = date('W');
                    $currentDay = date('Y-m-d');

                    foreach($transactions as $t) {
                        if($t['type']=='Chi' && strpos($t['date'], $currentMonth) === 0) {
                            $tongChiThang += $t['amount'];
                            
                            if(date('W', strtotime($t['date'])) == $currentWeek) {
                                $tongChiTuan += $t['amount'];
                            }
                            
                            if($t['date'] == $currentDay) {
                                $tongChiNgay += $t['amount'];
                            }
                        }
                    }

                    $percentThang = ($budget > 0) ? ($tongChiThang / $budget) * 100 : 0;
                    $barColorThang = ($percentThang > 100) ? 'bg-red-500' : 'bg-emerald-500';

                    $percentTuan = ($budgetWeekly > 0) ? ($tongChiTuan / $budgetWeekly) * 100 : 0;
                    $barColorTuan = ($percentTuan > 100) ? 'bg-red-500' : 'bg-blue-500';

                    $percentNgay = ($budgetDaily > 0) ? ($tongChiNgay / $budgetDaily) * 100 : 0;
                    $barColorNgay = ($percentNgay > 100) ? 'bg-red-500' : 'bg-amber-500';
                ?>

                <?php if ($percentThang > 100 || $percentTuan > 100 || $percentNgay > 100): ?>
                    <div class="mb-5 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm animate-pulse">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <span class="text-red-500 text-2xl">🚨</span>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-bold text-red-800 uppercase tracking-wide">Báo động đỏ!</h3>
                                <p class="text-sm text-red-700 mt-1">
                                    Bạn đã chi tiêu lố hạn mức của 
                                    <?php 
                                        $exceeded = [];
                                        if ($percentNgay > 100) $exceeded[] = 'Hôm nay';
                                        if ($percentTuan > 100) $exceeded[] = 'Tuần này';
                                        if ($percentThang > 100) $exceeded[] = 'Tháng này';
                                        echo "<span class='font-bold'>" . implode(', ', $exceeded) . "</span>";
                                    ?>. Hãy ngừng việc mua sắm lại ngay!
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="space-y-4">
                    <!-- Tháng -->
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-700">Tháng này</span>
                            <span class="font-bold text-gray-800"><?= number_format($tongChiThang) ?> / <?= number_format($budget) ?>đ</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                            <div class="h-full <?= $barColorThang ?> transition-all duration-500" style="width: <?= min($percentThang, 100) ?>%"></div>
                        </div>
                        <p class="text-[11px] text-right mt-1 <?= $percentThang > 100 ? 'text-red-500' : 'text-gray-400' ?>">
                            <?= round($percentThang, 1) ?>%
                        </p>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-700">Tuần này</span>
                            <span class="font-bold text-gray-800"><?= number_format($tongChiTuan) ?> / <?= number_format($budgetWeekly) ?>đ</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                            <div class="h-full <?= $barColorTuan ?> transition-all duration-500" style="width: <?= min($percentTuan, 100) ?>%"></div>
                        </div>
                        <p class="text-[11px] text-right mt-1 <?= $percentTuan > 100 ? 'text-red-500' : 'text-gray-400' ?>">
                            <?= round($percentTuan, 1) ?>%
                        </p>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-700">Hôm nay</span>
                            <span class="font-bold text-gray-800"><?= number_format($tongChiNgay) ?> / <?= number_format($budgetDaily) ?>đ</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                            <div class="h-full <?= $barColorNgay ?> transition-all duration-500" style="width: <?= min($percentNgay, 100) ?>%"></div>
                        </div>
                        <p class="text-[11px] text-right mt-1 <?= $percentNgay > 100 ? 'text-red-500' : 'text-gray-400' ?>">
                            <?= round($percentNgay, 1) ?>%
                        </p>
                    </div>
                </div>
            </div>

            <!-- PIE -->
            <div class="bg-white p-6 rounded-3xl border shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-600">Phân bổ chi tiêu</h3>
                    <input type="month" id="pieMonthSelector" value="<?= date('Y-m') ?>" max="<?= date('Y-m') ?>" class="border rounded-lg px-2 py-1 text-sm outline-none focus:border-emerald-500 text-gray-600 cursor-pointer">
                </div>
                <div class="w-full h-52">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>

        </div>
        <div class="p-10 bg-gradient-to-r from-emerald-50 to-blue-50 rounded-3xl border text-center shadow-sm">
            <h2 class="text-2xl font-bold text-emerald-700">Chào HIENLTH 👋</h2>
            <p class="text-gray-500 mt-1">
                Hôm nay là <?= date('d/m/Y') ?>. Chúc bạn quản lý tài chính thật tốt!
            </p>
        </div>

    </div>

<?php elseif ($page === 'history'): ?>

    <div class="max-w-5xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">📜 Lịch sử giao dịch</h2>
            <a href="?page=export_preview"
               class="text-sm bg-blue-50 text-blue-600 px-4 py-2 rounded-lg border hover:bg-blue-100 transition">
               ⬇ Xuất CSV
            </a>
        </div>
        <div class="bg-white rounded-3xl border shadow-sm divide-y overflow-hidden">

            <?php if(empty($transactions)): ?>
                <p class="p-10 text-center text-gray-400">Chưa có giao dịch.</p>
            <?php endif; ?>

            <?php foreach (array_reverse($transactions) as $t): ?>
                <div class="p-4 flex justify-between items-center hover:bg-gray-50 transition">
                    <div>
                        <p class="font-semibold text-gray-700">
                            <?= htmlspecialchars($t['category']) ?>
                        </p>
                        <p class="text-xs text-gray-400">
                            <?= $t['date'] ?> • <?= htmlspecialchars($t['note']) ?>
                        </p>
                    </div>

                    <p class="font-bold text-lg <?= $t['type']=='Thu'?'text-emerald-500':'text-red-500' ?>">
                        <?= ($t['type']=='Thu'?'+':'-') . number_format($t['amount']) ?>đ
                    </p>
                </div>
            <?php endforeach; ?>

        </div>

    </div>

<?php elseif ($page === 'chart'): ?>

    <div class="max-w-5xl mx-auto space-y-6">

        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">
                📊 Thống kê thu chi theo danh mục
            </h2>
            <input type="month" id="barMonthSelector" value="<?= date('Y-m') ?>" max="<?= date('Y-m') ?>" class="border rounded-lg px-4 py-2 outline-none focus:border-emerald-500 text-gray-600 cursor-pointer shadow-sm font-semibold">
        </div>

        <div class="bg-white p-6 rounded-3xl border shadow-sm hover:shadow-md transition">
            <div class="w-full h-[400px]">
                <canvas id="barChart"></canvas>
            </div>
        </div>

    </div>

<?php elseif ($page === 'export_preview'): ?>

    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">👀 Xem trước dữ liệu xuất</h2>
            <a href="?action=export" class="bg-emerald-500 text-white px-6 py-2 rounded-lg font-bold hover:bg-emerald-600 shadow-md transition-all">
                ⬇ Xác nhận tải về (CSV)
            </a>
        </div>
        
        <div class="bg-white rounded-3xl border shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="p-4 font-semibold text-gray-600">Ngày</th>
                            <th class="p-4 font-semibold text-gray-600">Loại</th>
                            <th class="p-4 font-semibold text-gray-600">Danh Mục</th>
                            <th class="p-4 font-semibold text-gray-600">Ghi Chú</th>
                            <th class="p-4 font-semibold text-gray-600 text-right">Số Tiền</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <?php if(empty($transactions)): ?>
                            <tr><td colspan="5" class="p-10 text-center text-gray-400">Không có dữ liệu.</td></tr>
                        <?php endif; ?>
                        <?php foreach (array_reverse($transactions) as $t): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 text-gray-700 whitespace-nowrap"><?= $t['date'] ?></td>
                                <td class="p-4">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $t['type']=='Thu'?'bg-emerald-100 text-emerald-700':'bg-red-100 text-red-700' ?>">
                                        <?= $t['type'] ?>
                                    </span>
                                </td>
                                <td class="p-4 text-gray-700 font-medium"><?= htmlspecialchars($t['category']) ?></td>
                                <td class="p-4 text-gray-500 text-sm max-w-xs truncate" title="<?= htmlspecialchars($t['note']) ?>"><?= htmlspecialchars($t['note']) ?></td>
                                <td class="p-4 font-bold text-right <?= $t['type']=='Thu'?'text-emerald-600':'text-red-600' ?>">
                                    <?= ($t['type']=='Thu'?'+':'-') . number_format($t['amount']) ?>đ
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php endif; ?>

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
            <input type="date" name="date" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" class="w-full border p-2 rounded-xl">
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
        const allTransactions = <?= json_encode($transactions) ?>;
        const cats = ['Ăn uống', 'Học tập', 'Giải trí', 'Mua sắm', 'Di chuyển'];
        
        let pieChart;
        const ctx = document.getElementById('pieChart').getContext('2d');
        
        function updatePieChart(monthStr) {
            let vals = [0, 0, 0, 0, 0];
            let totalMonthChi = 0;
            
            allTransactions.forEach(t => {
                if (t.type === 'Chi' && t.date.startsWith(monthStr)) {
                    let idx = cats.indexOf(t.category);
                    if (idx !== -1) {
                        vals[idx] += parseFloat(t.amount);
                        totalMonthChi += parseFloat(t.amount);
                    }
                }
            });
            
            if (pieChart) {
                pieChart.destroy();
            }
            
            pieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: cats,
                    datasets: [{
                        data: vals,
                        backgroundColor: ['#10b981','#3b82f6','#f59e0b','#ef4444','#8b5cf6'],
                        borderWidth: 0
                    }]
                },
                options: { 
                    maintainAspectRatio: false, 
                    plugins: { 
                        legend: { position: 'right' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.parsed || 0;
                                    let percentage = totalMonthChi > 0 ? ((value / totalMonthChi) * 100).toFixed(1) + '%' : '0%';
                                    return label + ': ' + value.toLocaleString('vi-VN') + 'đ (' + percentage + ')';
                                }
                            }
                        }
                    } 
                }
            });
        }
        
        const monthSelector = document.getElementById('pieMonthSelector');
        if (monthSelector) {
            monthSelector.addEventListener('change', (e) => {
                updatePieChart(e.target.value);
            });
            updatePieChart(monthSelector.value);
        }
    <?php endif; ?>
    <?php if ($page === 'chart'): ?>
        const allTransactions = <?= json_encode($transactions ?? []) ?>;
        
        let barChart;
        const ctxBar = document.getElementById('barChart').getContext('2d');
        
        function updateBarChart(monthStr) {
            // Group by category and type
            let categoryData = {};
            
            allTransactions.forEach(t => {
                if (t.date.startsWith(monthStr)) {
                    if (!categoryData[t.category]) {
                        categoryData[t.category] = { thu: 0, chi: 0 };
                    }
                    if (t.type === 'Thu') {
                        categoryData[t.category].thu += parseFloat(t.amount);
                    } else {
                        categoryData[t.category].chi += parseFloat(t.amount);
                    }
                }
            });
            
            let labels = Object.keys(categoryData);
            let thuData = labels.map(l => categoryData[l].thu);
            let chiData = labels.map(l => categoryData[l].chi);
            
            if (barChart) {
                barChart.destroy();
            }
            
            barChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: labels.length ? labels : ['Không có dữ liệu'],
                    datasets: [
                        {
                            label: 'Thu nhập',
                            data: labels.length ? thuData : [0],
                            backgroundColor: '#10b981',
                            borderRadius: 6
                        },
                        {
                            label: 'Chi tiêu',
                            data: labels.length ? chiData : [0],
                            backgroundColor: '#ef4444',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000) return (value / 1000) + 'k';
                                    return value;
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('vi-VN').format(context.parsed.y) + 'đ';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }
        
        const barMonthSelector = document.getElementById('barMonthSelector');
        if (barMonthSelector) {
            barMonthSelector.addEventListener('change', (e) => {
                updateBarChart(e.target.value);
            });
            updateBarChart(barMonthSelector.value);
        }
    <?php endif; ?>
</script>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/src/views/partials/application/AppFooter.php'; ?>