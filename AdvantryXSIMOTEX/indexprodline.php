<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SIMOTEX | DigiTex By Advantry X</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="img/favicon.ico" />

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

        #wrapper {
            height: 100%;
            overflow: hidden;
        }

        #content-wrapper {
            height: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .metabase-container {
            flex: 1;
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        .iframe-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .metabase-iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Hide Metabase footer */
        .metabase-iframe {
            margin-bottom: -100px;
            height: calc(100% + 80px);
            /* Compensate for the negative margin */
        }

        iframe.metabase-iframe {
            position: relative;
        }

        footer {
            flex-shrink: 0;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include("sideBare.php") ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper">
            <!-- Metabase Dashboard -->
            <div class="metabase-container">
                <div class="iframe-container">
                    <iframe
                        src="http://192.168.1.31:3000/public/dashboard/797de51a-418e-4a75-a7db-454198acf2db"
                        class="metabase-iframe"
                        allowtransparency="true"
                        frameborder="0"
                        allowfullscreen></iframe>
                </div>
            </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Advantry X <?php echo date("Y"); ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>