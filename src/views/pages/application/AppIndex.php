<?php
require $_SERVER['DOCUMENT_ROOT'] . '/src/views/partials/application/AppHeader.php';
?>
<div class="fixed top-0 left-0 w-full h-12 bg-gradient-to-r from-emerald-500 to-blue-500 text-white flex items-center px-6 z-50 center justify-center text-3xl ">
    <span class="font-semibold tracking-wide">Quản lý chi tiêu</span>
</div>
<div class="fixed top-12 left-0 w-full h-16 bg-white/70 backdrop-blur border-b shadow-sm flex items-center justify-between px-6 z-40">

    <div class="flex items-center gap-4">
        <div class="text-2xl font-bold bg-gradient-to-r from-emerald-500 to-blue-500 bg-clip-text text-transparent">
            HIENLTH
        </div>
    </div>
    <div class="flex items-center gap-4">
        <div class="text-lg font-semibold">
            Số dư: <span id="balance" class="text-green-500">0 VND</span>
        </div>

        <button id="openExpenseForm"
            class="inline-flex items-center justify-center gap-2 h-10 px-5 rounded-md bg-primary text-primary-foreground hover:opacity-90">
            + Thu / Chi
        </button>
    </div>

</div>
<div class="mt-32"></div>

<div class="mt-20"></div>

<div id="expenseModal" class="hidden fixed inset-0 bg-black/40 flex justify-center items-center z-50">
    
    <div class="w-full max-w-lg">
        <div class="flex flex-col justify-center items-center px-4 py-8">
            <div class="max-w-lg w-full space-y-4">
                <div class="flex items-center justify-between h-14 px-4 rounded-lg bg-card border text-xl font-bold shadow-sm">
                    <span>Quản lý chi tiêu</span>
                    <button id="closeExpenseForm">✕</button>
                </div>

                <form id="expenseForm" class="flex flex-col gap-5 p-6 rounded-lg bg-card border shadow-sm">
                    
                    <div>
                        <label>Số Tiền</label>
                        <input type="number" name="amount" required class="w-full border p-2 rounded">
                    </div>

                    <div>
                        <label>Loại</label>
                        <select id="typeSelect" name="type" required class="w-full border p-2 rounded">
                            <option value="">Chọn</option>
                            <option value="Thu">Thu</option>
                            <option value="Chi">Chi</option>
                        </select>
                    </div>

                    <div id="categoryBox">
                        <label>Danh Mục</label>
                        <select id="category" name="category" class="w-full border p-2 rounded">
                            <option>Ăn uống</option>
                            <option>Di chuyển</option>
                            <option>Mua sắm</option>
                            <option>Giải trí</option>
                            <option>Khác</option>
                        </select>
                    </div>

                    <div>
                        <label>Ngày</label>
                        <input type="date" name="date" required class="w-full border p-2 rounded">
                    </div>

                    <div>
                        <label>Ghi chú</label>
                        <textarea name="note" class="w-full border p-2 rounded"></textarea>
                    </div>

                    <button class="h-10 bg-primary text-white rounded hover:opacity-90">
                        Lưu Giao Dịch
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>
<div class="fixed top-auto left-0 h-[calc(100vh-4rem)] w-64 bg-white border-r shadow-sm">

    <div class="p-4 space-y-2">
        <div class="p-3 rounded-lg bg-emerald-100 text-emerald-600 font-medium">
            Trang chủ
        </div>
        <div class="p-3 rounded-lg hover:bg-gray-100 cursor-pointer">
            Lịch sử giao dịch 
        </div>
        <div class="p-3 rounded-lg hover:bg-gray-100 cursor-pointer">
            Danh mục giao dịch
        </div>
        <div class="p-3 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors duration-200">
            <div class="font-medium text-gray-700 mb-2 flex items-center gap-2">
                <span>📊</span> Thống kê
            </div>
            <select name="category" class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200">
                <option>Theo tuần</option>
                <option>Theo tháng</option>
            </select>
        </div>

        <div class="p-3 rounded-lg hover:bg-gray-100 cursor-pointer">
            Cài đặt
        </div>

    </div>

</div>

<script>
let transactions = [];

const openBtn = document.getElementById("openExpenseForm");
const closeBtn = document.getElementById("closeExpenseForm");
const modal = document.getElementById("expenseModal");

openBtn.onclick = () => modal.classList.remove("hidden");
closeBtn.onclick = () => modal.classList.add("hidden");

modal.onclick = (e) => {
    if (e.target === modal) modal.classList.add("hidden");
};
const form = document.getElementById("expenseForm");

const typeSelect = document.getElementById("typeSelect");
const categoryBox = document.getElementById("categoryBox");

typeSelect.addEventListener("change", function() {
    if (this.value === "Thu") {
        categoryBox.style.display = "none";
    } else {
        categoryBox.style.display = "block";
    }
});
form.addEventListener("submit", function(e) {
    e.preventDefault();

    const data = {
        amount: Number(form.amount.value),
        type: form.type.value,
        category: form.category.value,
        date: form.date.value,
        note: form.note.value
    };

    transactions.push(data);

    updateBalance();

    form.reset();
    modal.classList.add("hidden");
});

function updateBalance() {
    let thu = 0;
    let chi = 0;

    transactions.forEach(t => {
        if (t.type === "Thu") thu += t.amount;
        if (t.type === "Chi") chi += t.amount;
    });

    const balance = thu - chi;

    const balanceEl = document.getElementById("balance");
    balanceEl.innerText = balance.toLocaleString() + " VND";
    if (balance > 0) balanceEl.className = "text-green-500";
    else if (balance < 0) balanceEl.className = "text-red-500";
    else balanceEl.className = "text-gray-500";
}
</script>


<?php
require $_SERVER['DOCUMENT_ROOT'] . '/src/views/partials/application/AppFooter.php';
?>