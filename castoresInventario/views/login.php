<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image text-center">
                              <img src="assets/img/TransportesCastores.png" alt="Login Image" style="width: 100%; height: auto; margin-left: 20px; margin-top: 70px;">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Inventario Castores</h1>
                                    </div>
                                    <form action="<?php echo baseUrl ?>?controller=usuarios&action=login" method="POST">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="useremail" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address..." name="useremail">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="usePassword" placeholder="Password" name="usePassword">
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-user">
                                            Iniciar Sesión
                                        </button>

                                    </form>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</body>
