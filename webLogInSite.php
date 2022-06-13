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
        <link rel="stylesheet" type="text/css" href="css/rankingPop.css"/>

        <script type="text/javascript" src="js/ranking.js"></script>

    </head>
    <body>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>

        <div class="Title">
            <a href="javascript:openPop()">
                <div>
                    <p >랭킹</p>
                </div>
            </a>
            <h1>
                <div class="text-center p-t-10">
                    <span class="join"><?php echo $_SESSION['info_id'];?>님 오늘도 운동하러 오셨군요!</span>
                    <form class="logout-btn" action="php/logout.php" method="post">
                        <div style='margin-bottom:20px'>
                            <button class="logout-btn2">로그아웃</button>
                        </div>
                    </form>
                </div>

                <p>오늘도 푸쉬업</p>

                <div style='margin-bottom:15px'>
                    <a href="pushUpCam.php">
                        푸쉬업 시작하기
                    </a>
                </div>


                <p><?php echo $_SESSION['info_id'];?>님의 누적 <?php echo $_SESSION['info_count'];?>개</p>

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
                        const size = 499;
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
                    var status = "푸쉬업 올라감"
                    var count = 0
                    async function predict() {
                        // Prediction #1: run input through posenet estimatePose can take in an image,
                        // video or canvas html element
                        const {pose, posenetOutput} = await model.estimatePose(webcam.canvas);
                        // Prediction 2: run input through teachable machine classification model
                        const prediction = await model.predict(posenetOutput);
                        if (prediction[0].probability.toFixed(2) > 1.00) {
                            if (status == "푸쉬업 내려감") {
                                count++
                                var audio = new Audio('audio_file.mp3');
                                audio.play();
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
                    function stop() {
                        webcam.stop();
                    }
                </script>
            </h1>
        </div>

        <div class="popup_layer" id="popup_layer" style="display: none;">
            <div class="popup_box">
                <div style="height: 10px; width: 375px; float: top;"></div>
                <!--팝업 컨텐츠 영역-->
                <div class="popup_cont">
                    <h5>
                        실시간 랭킹
                    </h5>
                    <p>
                        
                    </p>

                </div>
                <!--팝업 버튼 영역-->
                <div class="popup_btn" style="float: bottom; margin-top: 200px;">
                    <a href="javascript:closePop();">닫기</a>
                </div>
            </div>
        </div>

        <div></div>
    </body>
</html>