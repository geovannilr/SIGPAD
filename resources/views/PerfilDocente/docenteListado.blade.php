<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="current-url" content="{{\Illuminate\Support\Facades\URL::to('/')}}">
    <title>.::Perfl Docente - EISI</title>


    <!-- Custom fonts for this template -->
    <link href="https://fonts.googleapis.com/css?family=Saira+Extra+Condensed:500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,800,800i" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- Bootstrap core CSS -->
{!!Html::style('PerfilDocente/vendor/fontawesome-free/css/all.min.css')!!}
{!!Html::style('PerfilDocente/vendor/bootstrap/css/bootstrap.min.css')!!}
{!!Html::style('PerfilDocente/css/resume.css')!!}
<!-- Bootstrap core CSS -->
    <style type="text/css">
        .customSectionH2Title{
            margin-top: 1em;margin-bottom: 1em;color: #212656  ; text-align: center;  font-family: 'Segoe UI'; font-size: 35px; text-transform: uppercase; letter-spacing: -2px;
        }
        .customSectionStyle{
            background-color: #ffffff; height: 100%; width: 100%;
        }
    </style>
</head>

<body id="page-top" style="background-color: #ffffff">

<div id="divDataEmpleados"></div>

<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content bmd-modalContent">

            <div class="modal-body">

                <div class="close-button">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" frameborder="0"></iframe>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Custom scripts for this template -->
{!!Html::script('PerfilDocente/vendor/jquery/jquery.min.js')!!}
{!!Html::script('PerfilDocente/vendor/bootstrap/js/bootstrap.bundle.min.js')!!}
{!!Html::script('PerfilDocente/vendor/jquery-easing/jquery.easing.min.js')!!}
{!!Html::script('PerfilDocente/js/main.js')!!}
<!-- Custom scripts for this template -->
{!!Html::script('PerfilDocente/js/resume.js')!!}
<!-- Plugin JavaScript -->
<script>
    $( document ).ready(function() {
        getListadoDocente({{$jornada}});
        $("a").click(function(e) {
            e.preventDefault();
            $("#myModal").attr("src", $(this).attr("href"));
        });
    });
</script>
</body>

</html>
