<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>다함께 푸쉬업</title>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <link rel="stylesheet" type="text/css" href="css/web.css"/>

    </head>
    <body>
        <script>
            window.onload = function () {
                init();
            }
        </script>
        <div class="Title">

            <a href="webLogInSite.php" style="margin-bottom:10px">
                오늘은 그만하기
            </a>

            <div>
                <canvas id="canvas"></canvas>
            </div>
            <div id="label-container"></div>
            <script
                src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@1.3.1/dist/tf.min.js"></script>
            <script
                src="https://cdn.jsdelivr.net/npm/@teachablemachine/pose@0.8/dist/teachablemachine-pose.min.js"></script>
            <script type="text/javascript">

                // More API functions here:
                // https://github.com/googlecreativelab/teachablemachine-community/tree/master/libraries/pose
                // the link to your model provided by Teachable Machine export panel
                const URL = "./my_model/";
                let model,
                    webcam,
                    ctx,
                    labelContainer,
                    maxPredictions;

                async function init() {
                    const modelURL = URL + "model.json";
                    const metadataURL = URL + "metadata.json";

                    // load the model and metadata Refer to tmImage.loadFromFiles() in the API to
                    // support files from a file picker
                    // Note: the pose library adds a tmPose object to your window (window.tmPose)
                    model = await tmPose.load(modelURL, metadataURL);
                    maxPredictions = model.getTotalClasses();

                    // Convenience function to setup a webcam
                    const size = 700;
                    const flip = true; // whether to flip the webcam
                    webcam = new tmPose.Webcam(size, size, flip); // width, height, flip
                    await webcam.setup(); // request access to the webcam
                    await webcam.play();
                    window.requestAnimationFrame(loop);

                    // append/get elements to the DOM
                    const canvas = document.getElementById("canvas");
                    canvas.width = size;
                    canvas.height = size;
                    ctx = canvas.getContext("2d");
                    labelContainer = document.getElementById("label-container");
                    for (let i = 0; i < maxPredictions; i++) { // and class labels
                        labelContainer.appendChild(document.createElement("div"));
                    }
                }

                async function loop(timestamp) {
                    webcam.update(); // update the webcam frame
                    await predict();
                    window.requestAnimationFrame(loop);
                }

                function countUp(){ 
                    <?php
                    $count = "UPDATE info set identify = 1 where info_id";
                    ?>
                }

                var status = "푸쉬업 올라감"
                var audio
                async function predict() {
                    // Prediction #1: run input through posenet estimatePose can take in an image,
                    // video or canvas html element
                    const {pose, posenetOutput} = await model.estimatePose(webcam.canvas);
                    // Prediction 2: run input through teachable machine classification model
                    const prediction = await model.predict(posenetOutput);
                    if (prediction[0].probability.toFixed(2) == 1.00) {
                        if (status == "푸쉬업 내려감") {
                            var audio = new Audio('audio_file.mp3');
                            audio.play();
                            countUp();
                        }
                        status = "푸쉬업 올라감"
                    } else if (prediction[1].probability.toFixed(2) == 1.00) {
                        status = "푸쉬업 내려감"
                    }
                    for (let i = 0; i < maxPredictions; i++) {
                        const classPrediction = prediction[i].className + ": " + prediction[i]
                            .probability
                            .toFixed(2);
                        labelContainer
                            .childNodes[i]
                            .innerHTML = classPrediction;
                    }

                    // finally draw the poses
                    drawPose(pose);
                }

                function drawPose(pose) {
                    if (webcam.canvas) {
                        ctx.drawImage(webcam.canvas, 0, 0);
                        // draw the keypoints and skeleton
                        if (pose) {
                            const minPartConfidence = 0.5;
                            tmPose.drawKeypoints(pose.keypoints, minPartConfidence, ctx);
                            tmPose.drawSkeleton(pose.keypoints, minPartConfidence, ctx);
                        }
                    }
                }

            </script>
        </h1>
    </div>
</body>
</html>