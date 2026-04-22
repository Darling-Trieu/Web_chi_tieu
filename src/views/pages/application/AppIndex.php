<?php require $_SERVER['DOCUMENT_ROOT'] . '/src/views/partials/application/AppHeader.php';
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="fixed top-0 left-0 w-full h-12 bg-primary text-primary-foreground flex items-center px-6 z-50 justify-center text-xl font-bold">
    Quản lý chi tiêu
</div>

<div class="fixed top-12 left-0 w-full h-16 bg-background/80 backdrop-blur border-b border-border shadow-sm flex items-center justify-between px-6 z-40">
    <div class="text-2xl font-bold text-foreground tracking-tight">HIENLTH</div>
    <div class="flex items-center gap-6">
        <div class="text-right">
            <p class="text-xs text-muted-foreground">Số dư hiện tại</p>
            <p class="font-bold text-lg <?= $balance >= 0 ? 'text-foreground' : 'text-destructive' ?>"><?= number_format($balance) ?>đ</p>
        </div>
        <button id="openExpenseForm" class="bg-primary text-primary-foreground px-4 py-2 rounded-lg font-bold hover:opacity-90 shadow-md transition-all">+ Giao dịch</button>
    </div>
</div>

<div class="flex pt-28">
    <div class="fixed left-0 w-64 h-full bg-card border-r border-border p-4 space-y-2">
        <a href="?page=home" class="block p-3 rounded-xl hover:bg-muted transition-colors <?= $page=='home'?'bg-secondary text-secondary-foreground font-bold':'text-foreground' ?>">🏠 Trang chủ</a>
        <a href="?page=history" class="block p-3 rounded-xl hover:bg-muted transition-colors <?= $page=='history'?'bg-secondary text-secondary-foreground font-bold':'text-foreground' ?>">📜 Lịch sử giao dịch</a>
        <a href="?page=chart" class="block p-3 rounded-xl hover:bg-muted transition-colors <?= $page=='chart'?'bg-secondary text-secondary-foreground font-bold':'text-foreground' ?>">
            📊 Biểu đồ tháng
        </a>
        <hr class="my-4 border-border">
        <a href="?page=export_preview" class="block p-3 rounded-xl transition-colors <?= $page=='export_preview'?'bg-primary text-primary-foreground font-bold':'hover:bg-muted text-foreground font-medium italic' ?>">📥 Xuất file Excel</a>
    </div>

    <div class="ml-64 flex-1 p-8">

<?php if ($page === 'home'): ?>

    <div class="max-w-6xl mx-auto space-y-6">

        <!-- GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- BUDGET -->
            <div class="bg-card p-6 rounded-3xl border border-border shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-foreground">Hạn mức chi tiêu</h3>
                    <form method="POST" class="flex gap-2">
                        <input type="number" name="budget_amount" min="0" step="1" oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null"
                            placeholder="Đổi tổng hạn mức"
                            class="border border-border rounded-lg px-3 py-1 text-sm w-36 focus:border-ring outline-none bg-background text-foreground">
                        <button name="set_budget"
                            class="bg-secondary text-secondary-foreground px-3 py-1 rounded-lg text-sm hover:bg-primary hover:text-primary-foreground transition">
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
                    $barColorThang = ($percentThang > 100) ? 'bg-destructive' : 'bg-primary';

                    $percentTuan = ($budgetWeekly > 0) ? ($tongChiTuan / $budgetWeekly) * 100 : 0;
                    $barColorTuan = ($percentTuan > 100) ? 'bg-destructive' : 'bg-foreground';

                    $percentNgay = ($budgetDaily > 0) ? ($tongChiNgay / $budgetDaily) * 100 : 0;
                    $barColorNgay = ($percentNgay > 100) ? 'bg-destructive' : 'bg-muted-foreground';
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
                            <span class="font-medium text-muted-foreground">Tháng này</span>
                            <span class="font-bold text-foreground"><?= number_format($tongChiThang) ?> / <?= number_format($budget) ?>đ</span>
                        </div>
                        <div class="w-full bg-muted h-2 rounded-full overflow-hidden">
                            <div class="h-full <?= $barColorThang ?> transition-all duration-500" style="width: <?= min($percentThang, 100) ?>%"></div>
                        </div>
                        <p class="text-[11px] text-right mt-1 <?= $percentThang > 100 ? 'text-destructive' : 'text-muted-foreground' ?>">
                            <?= round($percentThang, 1) ?>%
                        </p>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-muted-foreground">Tuần này</span>
                            <span class="font-bold text-foreground"><?= number_format($tongChiTuan) ?> / <?= number_format($budgetWeekly) ?>đ</span>
                        </div>
                        <div class="w-full bg-muted h-2 rounded-full overflow-hidden">
                            <div class="h-full <?= $barColorTuan ?> transition-all duration-500" style="width: <?= min($percentTuan, 100) ?>%"></div>
                        </div>
                        <p class="text-[11px] text-right mt-1 <?= $percentTuan > 100 ? 'text-destructive' : 'text-muted-foreground' ?>">
                            <?= round($percentTuan, 1) ?>%
                        </p>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-muted-foreground">Hôm nay</span>
                            <span class="font-bold text-foreground"><?= number_format($tongChiNgay) ?> / <?= number_format($budgetDaily) ?>đ</span>
                        </div>
                        <div class="w-full bg-muted h-2 rounded-full overflow-hidden">
                            <div class="h-full <?= $barColorNgay ?> transition-all duration-500" style="width: <?= min($percentNgay, 100) ?>%"></div>
                        </div>
                        <p class="text-[11px] text-right mt-1 <?= $percentNgay > 100 ? 'text-destructive' : 'text-muted-foreground' ?>">
                            <?= round($percentNgay, 1) ?>%
                        </p>
                    </div>
                </div>
            </div>

            <!-- PIE -->
            <div class="bg-card p-6 rounded-3xl border border-border shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-foreground">Phân bổ chi tiêu</h3>
                    <input type="month" id="pieMonthSelector" value="<?= date('Y-m') ?>" max="<?= date('Y-m') ?>" class="border border-border bg-background rounded-lg px-2 py-1 text-sm outline-none focus:border-ring text-foreground cursor-pointer">
                </div>
                <div class="w-full h-52">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>

        </div>
        <div class="p-10 bg-muted rounded-3xl border border-border text-center shadow-sm">
            <h2 class="text-2xl font-bold text-foreground">Chào HIENLTH 👋</h2>
            <p class="text-muted-foreground mt-1">
                Hôm nay là <?= date('d/m/Y') ?>. Chúc bạn quản lý tài chính thật tốt!
            </p>
        </div>

    </div>

<?php elseif ($page === 'history'): ?>

    <div class="max-w-5xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-foreground">📜 Lịch sử giao dịch</h2>
        </div>
        <div class="bg-card rounded-3xl border border-border shadow-sm divide-y divide-border overflow-hidden">

            <?php if(empty($transactions)): ?>
                <p class="p-10 text-center text-muted-foreground">Chưa có giao dịch.</p>
            <?php endif; ?>

            <?php foreach (array_reverse($transactions) as $t): ?>
                <div class="p-4 flex justify-between items-center hover:bg-muted/50 transition">
                    <div>
                        <p class="font-semibold text-foreground">
                            <?= htmlspecialchars($t['category']) ?>
                        </p>
                        <p class="text-xs text-muted-foreground">
                            <?= $t['date'] ?> • <?= htmlspecialchars($t['note']) ?>
                        </p>
                    </div>

                    <p class="font-bold text-lg <?= $t['type']=='Thu'?'text-foreground':'text-destructive' ?>">
                        <?= ($t['type']=='Thu'?'+':'-') . number_format($t['amount']) ?>đ
                    </p>
                </div>
            <?php endforeach; ?>

        </div>

    </div>

<?php elseif ($page === 'chart'): ?>

    <div class="max-w-5xl mx-auto space-y-6">

        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-foreground">
                📊 Thống kê thu chi theo danh mục
            </h2>
            <input type="month" id="barMonthSelector" value="<?= date('Y-m') ?>" max="<?= date('Y-m') ?>" class="border border-border bg-background rounded-lg px-4 py-2 outline-none focus:border-ring text-foreground cursor-pointer shadow-sm font-semibold">
        </div>

        <div class="bg-card p-6 rounded-3xl border border-border shadow-sm hover:shadow-md transition">
            <div class="w-full h-[400px]">
                <canvas id="barChart"></canvas>
            </div>
        </div>

    </div>

<?php elseif ($page === 'export_preview'): ?>

    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-foreground">👀 Xem trước dữ liệu xuất</h2>
            <a href="?action=export" class="bg-primary text-primary-foreground px-6 py-2 rounded-lg font-bold hover:opacity-90 shadow-md transition-all">
                ⬇ Xác nhận tải về (Excel)
            </a>
        </div>
        
        <div class="bg-card rounded-3xl border border-border shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-muted border-b border-border">
                            <th class="p-4 font-semibold text-foreground">Ngày</th>
                            <th class="p-4 font-semibold text-foreground">Loại</th>
                            <th class="p-4 font-semibold text-foreground">Danh Mục</th>
                            <th class="p-4 font-semibold text-foreground">Ghi Chú</th>
                            <th class="p-4 font-semibold text-foreground text-right">Số Tiền</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <?php if(empty($transactions)): ?>
                            <tr><td colspan="5" class="p-10 text-center text-muted-foreground">Không có dữ liệu.</td></tr>
                        <?php endif; ?>
                        <?php foreach (array_reverse($transactions) as $t): ?>
                            <tr class="hover:bg-muted/50 transition">
                                <td class="p-4 text-foreground whitespace-nowrap"><?= $t['date'] ?></td>
                                <td class="p-4">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full border border-border <?= $t['type']=='Thu'?'bg-background text-foreground':'bg-destructive/10 text-destructive' ?>">
                                        <?= $t['type'] ?>
                                    </span>
                                </td>
                                <td class="p-4 text-foreground font-medium"><?= htmlspecialchars($t['category']) ?></td>
                                <td class="p-4 text-muted-foreground text-sm max-w-xs truncate" title="<?= htmlspecialchars($t['note']) ?>"><?= htmlspecialchars($t['note']) ?></td>
                                <td class="p-4 font-bold text-right <?= $t['type']=='Thu'?'text-foreground':'text-destructive' ?>">
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

<div id="expenseModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm flex justify-center items-center z-[100] p-4">
    <div class="bg-card border border-border w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
        <form method="POST" class="p-6 space-y-4 text-foreground">
            <div class="flex justify-between items-center border-b border-border pb-4">
                <span class="text-xl font-bold">Thêm Giao Dịch</span>
                <button type="button" id="closeExpenseForm" class="text-muted-foreground hover:text-foreground">✕</button>
            </div>
            <input type="number" name="amount" placeholder="0 VND" required class="w-full text-3xl font-bold text-foreground bg-transparent border-none focus:ring-0 p-0 text-center placeholder:text-muted-foreground">
            <div class="grid grid-cols-2 gap-4">
                <select name="type" id="typeSelect" class="border border-border bg-background text-foreground p-2 rounded-xl outline-none focus:border-ring">
                    <option value="Chi">Khoản Chi</option>
                    <option value="Thu">Khoản Thu</option>
                </select>
                <select name="category" id="categoryBox" class="border border-border bg-background text-foreground p-2 rounded-xl outline-none focus:border-ring">
                    <option>Ăn uống</option><option>Học tập</option><option>Giải trí</option><option>Mua sắm</option><option>Di chuyển</option>
                </select>
            </div>
            <input type="date" name="date" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" class="w-full border border-border bg-background text-foreground p-2 rounded-xl outline-none focus:border-ring">
            <textarea name="note" placeholder="Ghi chú thêm..." class="w-full border border-border bg-background text-foreground p-2 rounded-xl outline-none focus:border-ring"></textarea>
            <button type="submit" class="w-full py-4 bg-primary text-primary-foreground rounded-xl font-bold hover:opacity-90 shadow-lg transition-opacity">LƯU THÔNG TIN</button>
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
                        backgroundColor: ['#0d9488', '#2563eb', '#ca8a04', '#dc2626', '#7c3aed'],
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
                            backgroundColor: '#0d9488',
                            borderRadius: 6
                        },
                        {
                            label: 'Chi tiêu',
                            data: labels.length ? chiData : [0],
                            backgroundColor: '#dc2626',
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