
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("expenseForm");
    const balanceElement = document.getElementById("currentBalance");

    const STORAGE_KEY = "current_balance";

    const formatCurrency = (value) => {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
    };

    const getBalance = () => {
        const raw = localStorage.getItem(STORAGE_KEY);
        const parsed = Number(raw);
        return Number.isFinite(parsed) ? parsed : 0;
    };

    const setBalance = (value) => {
        localStorage.setItem(STORAGE_KEY, value.toString());
        balanceElement.textContent = formatCurrency(value);

        if (value >= 0) {
            balanceElement.parentElement.style.background = 'linear-gradient(90deg, #38b6ff, #1f8eff)';
        } else {
            balanceElement.parentElement.style.background = 'linear-gradient(90deg, #ff5f6d, #ffc371)';
        }
    };

    const fields = {
        amount: {
            element: document.getElementById("amount"),
            error: document.getElementById("amountError"),
            validate: value => value > 0 ? "" : "Vui lòng nhập số tiền hợp lệ."
        },
        type: {
            element: document.getElementById("type"),
            error: document.getElementById("typeError"),
            validate: value => value ? "" : "Vui lòng chọn loại giao dịch."
        },
        category: {
            element: document.getElementById("category"),
            error: document.getElementById("categoryError"),
            validate: value => value ? "" : "Vui lòng chọn danh mục."
        },
        date: {
            element: document.getElementById("date"),
            error: document.getElementById("dateError"),
            validate: value => value ? "" : "Vui lòng chọn ngày giao dịch."
        }
    };

    const showError = (field, message) => {
        field.error.textContent = message;
        field.element.classList.toggle("input-error", !!message);
    };

    setBalance(getBalance());

    form.addEventListener("submit", (e) => {
        e.preventDefault();

        let isValid = true;

        for (let key in fields) {
            const field = fields[key];
            const value = field.element.value.trim();
            const errorMessage = field.validate(value);
            showError(field, errorMessage);
            if (errorMessage) isValid = false;
        }

        if (!isValid) {
            return;
        }

        const amount = parseFloat(fields.amount.element.value);
        const type = fields.type.element.value;

        let current = getBalance();
        current += (type === "Thu" ? amount : -amount);

        setBalance(current);

        form.reset();

        alert(`Đã cập nhật số tiền hiện có: ${formatCurrency(current)}`);
    });

    for (let key in fields) {
        const field = fields[key];
        field.element.addEventListener("input", () => showError(field, ""));
    }
});