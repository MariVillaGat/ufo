<?php require 'header.php'; ?>

        <div class="row">
            <div class="col">
                <h1>UFO submit result</h1>
                <?php
                // check if scary isset
                if(isset($_POST['scary'])){
                    $scary = 1;
                    echo "<div class='alert alert-danger' role='alert'>
                    Warning: DO NOT INTERACT WITH THE MONSTER!
                  </div>";
                } else {
                    $scary = 0;
                }
                // print_r($_POST);
                // print_r($_FILES);
                // check if file is uploaded
                
                $filename = generateRandomString(12).'.jpg';
                move_uploaded_file($_FILES['alienImg']['tmp_name'],'assets/images/'.$filename);

                $name = sanitizeInput($_POST['name']);
                $email = sanitizeInput($_POST['email']);
                $message = sanitizeInput($_POST['message']);
                $location = sanitizeInput($_POST['location']);
                $date = $_POST['date'];
                $time = $_POST['time'];

                // PDF SNIPPET

                // reference the Dompdf namespace
                use Dompdf\Dompdf;

                // PDF SNIPPET
                $pathImg = 'assets/images/'.$filename;
                $getImg = file_get_contents($pathImg);
                $encimage = base64_encode($getImg);
                

                $html = '<h1 style="text-align: center; color: #008000;">UFO Certificate</h1> <hr>';
                $html .= '<h2 style="font-size: 18px;">You have been in contact with a UFO</h2>';
                $html .= '<p style="font-size: 16px;">Name: '.$name.'</p>';
                $html .= '<p style="font-size: 16px;">Email: '.$email.'</p>';
                $html .= '<p style="font-size: 16px;">Location: '.$location.'</p>';
                $html .= '<p style="font-size: 16px;">Date: '.formatDate($date).'</p>';
                $html .= '<p style="font-size: 16px;">Time: '.$time.'</p>';
                $html .= '<p style="font-size: 16px;">Message: '.$message.'</p>';
                $html .= '<p><img src="data:image/svg+xml;base64,' . $encimage . '"</p>';
                // $html .= '<p>Image: <img src="http://localhost:8000/assets/images/'.$filename.'" width="200"></p>';   
                $html .= '<p style="font-size: 16px;">Scary: '.($scary ? 'Yes' : 'No').'</p> <br>';
                $html .= '<h1 style="font-size: 18px; color: #008000;">Thank you for your submission</h1>';

                // instantiate and use the dompdf class
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);

                // (Optional) Setup the paper size and orientation
                $dompdf->setPaper('A4', 'portrait');

                // Render the HTML as PDF
                $dompdf->render();
                ob_end_clean();
                
                // Output the generated PDF to Browser
                $dompdf->stream();


                
                die();
                
                $insertQuery = "INSERT INTO `aliens` (`name`, `email`, `location`, `date`, `time`, `scary`, `message`, `alienImg`, `approved`) 
                VALUES ('$name', '$email', '$location', '$date', '$time', $scary, '$message', '$filename',0)";

                // execute the query
                $result = $conn->query($insertQuery);

                ?>
                <h2>Personal</h2>
                <p>Name: <?php echo $name;?><p>
                <p>Email: <?php echo $email;?></p>
                <h2>Location</h2>
                <p>Location: <?php echo $location;?></p>
                <p>Date: <?php echo $date;?></p>
                <p>Time: <?php echo $time;?></p>
                <hr>
                <h2>Description</h2>
                <p><?php echo $message;?></p>
                <hr>
                <h2>Image</h2>
                <img src="assets/images/<?php echo $filename?>" width="200">
            </div>
        </div>

<?php require 'footer.php' ?>