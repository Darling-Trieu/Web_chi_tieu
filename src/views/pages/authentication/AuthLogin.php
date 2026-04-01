<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="/public/css/globals.css">    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Đăng nhập</title>
        <link rel="stylesheet" href="/public/css/globals.css">
    </head>
    <body class="min-h-screen bg-background flex items-center justify-center px-4 py-8">
        <main class="w-full max-w-sm">
            <div class="flex items-center gap-2.5 h-14 px-4 rounded-lg bg-card border border-border text-foreground text-xl font-bold tracking-tight shadow-sm mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                <span>Xin chào trở lại</span>
            </div>
    
            <p class="text-center text-muted-foreground mb-6">Đăng nhập vào tài khoản quản lý chi tiêu của bạn</p>
    
            <div id="loginStatus" class="text-center mb-4 text-sm text-destructive"></div>
    
            <form id="loginForm" action="/login" method="POST" class="flex flex-col gap-5 p-6 rounded-lg bg-card border border-border shadow-sm">
                <div class="flex flex-col gap-1.5">
                    <label for="username" class="text-sm font-medium leading-none text-foreground select-none tracking-wide">Tên đăng nhập</label>
                    <input id="username" name="username" type="text" required 
                           class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground/65 outline-none transition-colors focus:border-ring focus:ring-2 focus:ring-ring/25 hover:border-input/80" 
                           placeholder="Nhập tên đăng nhập">
                    <p class="text-xs text-destructive" id="usernameError"></p>
                </div>
    
                <div class="flex flex-col gap-1.5">
                    <label for="password" class="text-sm font-medium leading-none text-foreground select-none tracking-wide">Mật khẩu</label>
                    <div class="relative w-full">
                        <input id="password" name="password" type="password" required 
                               class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground/65 outline-none transition-colors focus:border-ring focus:ring-2 focus:ring-ring/25 hover:border-input/80 pr-10" 
                               placeholder="Nhập mật khẩu">
                        <button id="passwordToggle" type="button" 
                                class="absolute top-1/2 right-3 -translate-y-1/2 text-xs text-muted-foreground hover:text-foreground transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    <p class="text-xs text-destructive" id="passwordError"></p>
                </div>
    
                <button id="loginSubmit" type="submit" 
                        class="inline-flex items-center justify-center gap-2 h-10 px-5 rounded-md bg-primary text-primary-foreground text-sm font-medium whitespace-nowrap cursor-pointer transition-all duration-150 outline-none select-none hover:bg-primary/90 focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background active:scale-[0.98] disabled:pointer-events-none disabled:opacity-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8m4-9-4-4m4 4l-4 4m4-4h-4"/></svg>
                    Đăng nhập
                </button>
            </form>
    
            <p class="mt-6 text-sm text-muted-foreground text-center">
                Chưa có tài khoản? <a href="/register" class="text-primary hover:underline font-medium">Đăng ký ngay</a>
            </p>
        </main>
    
        <script src="/public/js/login.js"></script>
    </body>
    </html>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">
    <main class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
        <h1 class="text-3xl font-bold text-slate-800 mb-4 text-center">Xin chào trở lại</h1>
        <p class="text-center text-slate-500 mb-7">Đăng nhập vào tài khoản quản lý chi tiêu của bạn</p>

        <div id="loginStatus" class="text-center mb-4"></div>

        <form id="loginForm" action="/login" method="POST" class="space-y-4">
            <div>
                <label for="username" class="block text-sm font-medium text-slate-600">Tên đăng nhập</label>
                <input id="username" name="username" type="text" required class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200" placeholder="Nhập tên đăng nhập">
                <p class="mt-1 text-xs text-red-500"></p>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-600">Mật khẩu</label>
                <div class="relative mt-1">
                    <input id="password" name="password" type="password" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200" placeholder="Nhập mật khẩu">
                    <button id="passwordToggle" type="button" class="absolute top-1/2 right-2 -translate-y-1/2 text-xs text-slate-500 hover:text-slate-700">Hiện</button>
                </div>
                <p class="mt-1 text-xs text-red-500"></p>
            </div>

            <button id="loginSubmit" type="submit" class="w-full rounded-lg bg-blue-600 text-white px-4 py-2 font-semibold hover:bg-blue-700 transition">Đăng nhập</button>
        </form>

        <p class="mt-6 text-sm text-slate-400 text-center">Chưa có tài khoản? <a href="/register" class="text-blue-600 hover:underline">Đăng ký ngay</a></p>
    </main>

    <script src="/public/js/login.js"></script>
</body>
</html>