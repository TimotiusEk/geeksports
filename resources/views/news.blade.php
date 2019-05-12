<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')
    <style>
        pre {
            white-space: pre-wrap;
            word-break: keep-all;
        }
    </style>
</head>

<body style="background-color: whitesmoke;">

<div class="container" style="background-color: white; margin-top: 30px; border-radius: 10px;">
    <div class="container"
         style="background-color: white; border-radius: 10px; border-color: #491217; width: 99%; margin: 2%">
        <div class="row">
            <div class="col-md-1 text-center"></div>
            <div class="col-md-10" style="font-size: 40px; vertical-align: top;">
                <div>
                    <p style="font-size: 12px; margin-left: -30px;">{{$news->published_on}}</p>
                    <p style="margin-left: -30px; margin-top: -15px"><b>{{$news->title}}</b>
                    </p>
                    <hr>

                    <div align="center"> <img style=" height: 400px; width: 700px; margin-left: -35px; margin-top: 15px; margin-bottom: 15px;" src="/images/news_header/{{$news->header_image}}"/></div>
                    <p style="font-size: 20px;">
                    <pre style="font-size: 20px; background-color: white; border-width: 0px; font-family: 'Arial';">{{$news->content}}</pre>
                    </p>
                </div>
            </div>
            <div class="col-md-1 text-center"></div>
        </div>
    </div>
    <hr>
</div>
</body>
</html>