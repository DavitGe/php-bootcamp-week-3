<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Challange 3</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body>
    <main>
        <?php 

        //mysql
        $serverName = "localhost";
        $userName = "root";
        $password = "admin";
        $dbName = "challange3";

        $conn = mysqli_connect($serverName, $userName, $password, $dbName);

        if(mysqli_connect_errno()){
            echo "Failed to connect!";
            exit();
        }else{
            //getting data
            $sql = 'SELECT name, id FROM students';
            $result = $conn->query($sql);

            $output = [];
            if($result->num_rows > 0) {
                //check data of each row
                while($row = $result->fetch_assoc()){
                    $decoded_row = json_decode(json_encode($row));
                    if(strtolower($decoded_row->name) === strtolower($_POST['find'])) array_push($output, $decoded_row->id);
                }
            }
            if($output !== []){
                echo "output data";
                # output data
            }else{
            //options for getting content
                $opts = array(
                    'http'=>array(
                        'method'=>"GET",
                        'header'=>'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.54 Safari/537.36'
                        )
                    );
                    
                $context = stream_context_create($opts);
                    
                //getting data
                $url = "http://hp-api.herokuapp.com/api/characters/students";
                $json = file_get_contents($url, false, $context);
                $data = json_decode($json);

                //filtering by user input
                function test_studs($stud){
                    $name = strtolower($stud->name);
                    $name_inp = strtolower($_POST['find']);
                    return(str_contains($name, $name_inp));
                }

                //saving data
                $found = array_filter($data, "test_studs");
                if(!$found){
                    echo "not found";
                    #not found design
                }else{

                    foreach ($found as &$stud) {
                        $alternate_names = implode($stud->alternate_names);
                        if ($alternate_names == '') $alternate_names = '[]';
                        $alternate_actors = implode($stud->alternate_actors);
                        if ($alternate_actors == '') $alternate_actors = '[]'; 
                        $wizard = true;
                        if ($stud->wizard === FALSE) $wizard = 0;
                        $staff = true;
                        if ($stud->hogwartsStaff === FALSE) $staff = 0;
                        $student = true;
                        if ($stud->hogwartsStudent === FALSE) $student = 0;
                        $alive = true;
                        if ($stud->alive === FALSE) $alive = 0;


                        $sql = "INSERT INTO `students` (`name`, `alternate_names`, `species`, `gender`, `house`, `dateOfBirth`, `yearOfBirth`, `wizard`, `ancestry`, `eyeColour`, `hairColour`, `wand`, `patronus`, `hogwartsStudent`, `hogwartsStaff`, `actor`, `alternate_actors`, `alive`, `image`) 
                                VALUES ('$stud->name', '$alternate_names', '$stud->species', '$stud->gender','$stud->house', '$stud->dateOfBirth', '$stud->yearOfBirth', '$wizard', '$stud->ancestry', '$stud->eyeColour', '$stud->hairColour', '$wand', '$stud->patronus', '$student', '$staff', '$stud->actor', '$alternate_actors', '$alive', '$stud->image')";
                        try{
                            if($conn->query($sql)){
                                echo "New record created successfully";
                            }else{
                                echo "Error: " . $sql . "<br>" . $conn->error;
                            }
                        }catch(mysqli_sql_exception $e){
                            print "<pre>";
                            var_dump($e);
                            print "</pre>";
                            exit;
                        }
                    }
                }   

            }
        }
        ?>
    </main>
	<script src="index.js"></script>
  </body>
</html>
