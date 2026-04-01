const select = (selector) => {
    return document.querySelector(selector);
};
const setError = (input, message) => {
    var _a;
    const errorEl = (_a = input.nextElementSibling) !== null && _a !== void 0 ? _a : null;
    if (errorEl)
        errorEl.textContent = message;
    input.classList.toggle("border-red-500", !!message);
};
const clearError = (input) => setError(input, "");
const validateText = (value, fieldName) => {
    if (!value || value.trim().length === 0)
        return `${fieldName} không được để trống.`;
    if (value.trim().length < 4)
        return `${fieldName} phải có ít nhất 4 ký tự.`;
    return "";
};
window.addEventListener("DOMContentLoaded", () => {
    const form = select("#loginForm");
    const username = select("#username");
    const password = select("#password");
    const status = select("#loginStatus");
    if (!form || !username || !password || !status)
        return;
    const showStatus = (text, success) => {
        status.textContent = text;
        status.className = success
            ? "text-green-600 font-medium"
            : "text-red-600 font-medium";
    };
    username.addEventListener("input", () => clearError(username));
    password.addEventListener("input", () => clearError(password));
    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        let isValid = true;
        const usernameError = validateText(username.value, "Tên đăng nhập");
        const passwordError = validateText(password.value, "Mật khẩu");
        if (usernameError) {
            setError(username, usernameError);
            isValid = false;
        }
        if (passwordError) {
            setError(password, passwordError);
            isValid = false;
        }
        if (!isValid) {
            showStatus("Vui lòng sửa lỗi trước khi tiếp tục.", false);
            return;
        }
        showStatus("Đang xử lý đăng nhập...", true);
        const data = new FormData(form);
        try {
            const response = await fetch(form.action, {
                method: "POST",
                body: data,
                headers: {
                    "Accept": "application/json"
                }
            });
            if (!response.ok) {
                const text = await response.text();
                showStatus(`Đăng nhập thất bại: ${text}`, false);
                return;
            }
            const result = await response.json();
            if (result.ok) {
                showStatus(result.message || "Đăng nhập thành công.", true);
                setTimeout(() => {
                    window.location.href = result.redirect || "/";
                }, 500);
            }
            else {
                showStatus(result.message || "Tên đăng nhập hoặc mật khẩu không chính xác.", false);
            }
        }
        catch (error) {
            showStatus("Lỗi mạng, vui lòng thử lại sau.", false);
            console.error(error);
        }
    });
});
