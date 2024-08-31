<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

Models:

    User and Positions with relationships one-to-many. Used belongsTo and hasMany funcs.

Controllers:

    UserController
        showAllUsers() - get all users for view
        showUser() - get one user for view
        getAllUsersApi() - get all users for api
        getUserByIdApi() - get one user for api
    
    RegisterController
        showRegistrationForm() - show form for view
        registerUser() - register from view
        registerUserApi() - register from api
    
    PositionController
        index() - get all positions for view
    
    TokenController
        getToken() - get new token for register, valid for 40 minutes
Views:

    users
        all.blade.php - show all users with 6 on page, pagination and Show more button
        one.blade.php - show one user info
    auth
        register.blade.php - show registration form
Routes

    API
        Route::post('/users', [RegisterController::class, 'registerUserApi']);
        Route::get('/users', [UserController::class, 'getAllUsersApi']);
        Route::get('/users/{id}', [UserController::class, 'getUserByIdApi']);
        Route::get('/positions', [PositionController::class, 'index']);
        Route::get('/token', [TokenController::class, 'getToken']);
    WEB
        Route::get('/users', [UserController::class, 'showAllUsers'])->name('users.showAllUsers');
        Route::get('/users/{id}', [UserController::class, 'showUser'])->name('users.showUser');
        Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
        Route::post('/register', [RegisterController::class, 'registerUser'])->name('register.submit');
    
To optimize the photo at the time of registration (API and front registration), the TinyPNG service was used. Simple connect, docs and just easy for demonstrations api skills. The image cropped, optimized and saved as jpg 70x70px.


