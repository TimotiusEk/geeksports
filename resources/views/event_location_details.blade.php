<html>
<head>
    <?php include 'php/required_css.php'; ?>
    <?php include 'php/required_js.php'; ?>
    @include('nav_header')

    <script>
        function enlargePicture(id) {
            // Get the modal
            var modal = document.getElementById('imageModal');

            // Get the image and insert it inside the modal - use its "alt" text as a caption
            var img = document.getElementById(id);
            var modalImg = document.getElementById("img01");
            var captionText = document.getElementById("caption");

            modal.style.display = "block";
            modalImg.src = img.src;
            captionText.innerHTML = img.alt;

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks on <span> (x), close the modal
            span.onclick = function () {
                modal.style.display = "none";
            }
        }
    </script>

    <style>
        /* Style the Image Used to Trigger the Modal */
        #myImg {
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        #myImg:hover {
            opacity: 0.7;
        }

        /* The Modal (background) */
        .modal_2 {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0, 0, 0); /* Fallback color */
            background-color: rgba(0, 0, 0, 0.9); /* Black w/ opacity */
        }

        /* Modal Content (Image) */
        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        /* Caption of Modal Image (Image Text) - Same Width as the Image */
        #caption {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            text-align: center;
            color: #ccc;
            padding: 10px 0;
            height: 150px;
        }

        /* Add Animation - Zoom in the Modal */
        .modal-content, #caption {
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        @keyframes zoom {
            from {
                transform: scale(0)
            }
            to {
                transform: scale(1)
            }
        }

        /* The Close Button */
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        /* 100% Image Width on Smaller Screens */
        @media only screen and (max-width: 700px) {
            .modal-content {
                width: 100%;
            }
        }

        .media {
            cursor: pointer;
        }
    </style>
</head>
<body style="background-color: whitesmoke">

<div class="container">
    <div class="row" style="background-color: white; border-radius: 10px; margin-top: 2%">
        <div class="col-md-3 text-center"><img
                    style="height: 150px; width: 225px;margin-top: 15px; padding-top: 45px; padding-left: 30px"
                    src="/images/sample_event_location_logo_1.png"/></div>
        <div class="col-md-9" style="font-size: 40px; vertical-align: top;">
            <div style="margin-left: 70px">
                <p style="margin-top: 20px;">
                    <b style="color: black; font-size: 30px">
                        {{$event_location->name}}
                    </b>
                </p>
                <hr>
                <div style="font-size: 23px; margin-top: -10px"><img src="images/ic_location.png"
                                                                     style="width: 30px; margin-right: 10px; margin-bottom: 10px;">
                    {{$event_location->city}}
                </div>

                <div style="font-size: 23px;">
                    <a href="{{$event_location->gmaps_url}}">See in Google Maps</a>
                </div>

                <div style="background-color: #f4f4f4; font-size: 23px; padding: 1%; margin: 2%">
                        <pre style="font-size: 23px; border-width: 0px; font-family: 'Arial';">{{$event_location->contact_person}}
                        </pre>
                </div>
            </div>
        </div>
    </div>

    @if(count($event_location_media) != 0)
        <div class="row" style="background-color: white; margin-top: 30px; border-radius: 10px; padding: 2%;">
            <p style="margin-top: 20px; margin-left: 10px; font-size: 30px; color: black"><b>Media</b></p>
            <hr style="width: 95%">

            @foreach($event_location_media as $media)
            <img src="/images/{{$media->event_location_picture}}" height="200px" width="350px"
                 style="margin-top: 25px ; margin-left: 17px" onclick="enlargePicture('event_location_{{$media->id}}')"
                 id="event_location_{{$media->id}}" alt="{{$media->picture_description}}" class="media"/>
            @endforeach
        </div>
    @endif
</div>

<!-- Show Image Modal -->
<div id="imageModal" class="modal_2">

    <!-- The Close Button -->
    <span class="close">&times;</span>

    <!-- Modal Content (The Image) -->
    <img class="modal-content" id="img01">

    <!-- Modal Caption (Image Text) -->
    <div id="caption"></div>
</div>
</body>
</html>