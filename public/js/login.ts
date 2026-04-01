type LoginResult = {
    ok: boolean;
    message: string;
    redirect?: string;
};

const select = <T extends HTMLElement>(selector: string): T | null => {
    return document.querySelector(selector) as T | null;
};

const setError = (input: HTMLInputElement, message: string) => {
    const errorEl = input.nextElementSibling as HTMLElement | null;
    if (errorEl) errorEl.textContent = message;
    input.classList.toggle("border-red-500", !!message);
    input.classList.toggle("ring-2", !!message);
    input.classList.toggle("ring-red-300", !!message);
};

const clearError = (input: HTMLInputElement) => setError(input, "");

const validateText = (value: string, fieldName: string): string => {
    if (!value || value.trim().length === 0) return `${fieldName} không được để trống.`;
    if (value.trim().length < 4) return `${fieldName} phải có ít nhất 4 ký tự.`;
    return "";
};

const setLoading = (button: HTMLButtonElement, isLoading: boolean) => {
    button.disabled = isLoading;
    button.textContent = isLoading ? "Đang đăng nhập..." : "Đăng nhập";
    button.classList.toggle("opacity-70", isLoading);
};

window.addEventListener("DOMContentLoaded", () => {
    const form = select<HTMLFormElement>("#loginForm");
    const username = select<HTMLInputElement>("#username");
    const password = select<HTMLInputElement>("#password");
    const status = select<HTMLDivElement>("#loginStatus");
    const submitBtn = select<HTMLButtonElement>("#loginSubmit");

    if (!form || !username || !password || !status || !submitBtn) return;

    const showStatus = (text: string, success: boolean) => {
        status.textContent = text;
        status.className = success
            ? "text-green-600 font-medium mb-3"
            : "text-red-600 font-medium mb-3";
    };

    const clearStatus = () => {
        status.textContent = "";
        status.className = "";
    };

    username.addEventListener("input", () => {
        clearError(username);
        clearStatus();
    });
    password.addEventListener("input", () => {
        clearError(password);
        clearStatus();
    });

    const togglePassword = () => {
        password.type = password.type === "password" ? "text" : "password";
    };

    const passwordToggleBtn = select<HTMLButtonElement>("#passwordToggle");
    if (passwordToggleBtn) {
        passwordToggleBtn.addEventListener("click", (event) => {
            event.preventDefault();
            togglePassword();
            passwordToggleBtn.textContent = password.type === "password" ? "Hiện" : "Ẩn";
        });
    }

    form.addEventListener("submit", async (event) => {
        event.preventDefault();

        clearStatus();

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

        setLoading(submitBtn, true);
        showStatus("Đang xử lý đăng nhập...", true);

        const data = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: "POST",
                body: data,
                headers: { "Accept": "application/json" },
            });

            if (!response.ok) {
                const errorText = await response.text();
                showStatus(`Đăng nhập thất bại: ${errorText || response.statusText}`, false);
                return;
            }

            const result = (await response.json()) as LoginResult;
            if (result.ok) {
                showStatus(result.message || "Đăng nhập thành công.", true);
                setTimeout(() => {
                    window.location.href = result.redirect || "/";
                }, 500);
            } else {
                showStatus(result.message || "Tên đăng nhập hoặc mật khẩu không chính xác.", false);
            }
        } catch (error) {
            console.error(error);
            showStatus("Lỗi mạng, vui lòng thử lại sau.", false);
        } finally {
            setLoading(submitBtn, false);
        }
    });
});

// Make this file an ES module to avoid global block-scoped redeclaration errors in editor/tsserver.
export {};
