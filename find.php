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
        
        if(mysqli_connect_errno()): ?>
            <div class="container mx-auto grid justify-items-center mt-12">
                <p class="decoration-2 text-red-600 bg-red-200 p-4 pt-2 pb-2 rounded-lg">Failed to connect with database.</p>
                <a class="underline" href="/">Go to home</a>
            </div>
        <?php else: ?>
            <?php
                //getting data
                $sql = 'SELECT id, name, alternate_names, species, gender, house, dateOfBirth, yearOfBirth, wizard, ancestry, eyeColour, hairColour, wand, patronus, hogwartsStudent, hogwartsStaff, actor, alternate_actors, alive, image FROM students';
                $result = $conn->query($sql);

                $output = null;
                if($result->num_rows > 0) {
                    //check data of each row
                    while($row = $result->fetch_assoc()){
                        $decoded_row = json_decode(json_encode($row));
                        if(strtolower($decoded_row->name) === strtolower($_POST['find'])) $output = $decoded_row;
                    }
                }
                if($output === null): ?>
                    <?php
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
                            echo '<div class="container mx-auto grid justify-items-center mt-12"><p class="decoration-2 text-red-600 bg-red-200 p-4 pt-2 pb-2 rounded-lg">Personage with such name is not found.</p><a class="underline" href="/">Go to home</a></div>';
                        }else{
                            foreach ($found as &$stud) {

                                $alternate_names = implode(',', $stud->alternate_names);
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
                                $yearOfBirth = 0;
                                if(gettype($stud->yearOfBirth) === "integer") $yearOfBirth = $stud->yearOfBirth;


                                $sql = "INSERT INTO `students` (`name`, `alternate_names`, `species`, `gender`, `house`, `dateOfBirth`, `yearOfBirth`, `wizard`, `ancestry`, `eyeColour`, `hairColour`, `wand`, `patronus`, `hogwartsStudent`, `hogwartsStaff`, `actor`, `alternate_actors`, `alive`, `image`) 
                                        VALUES ('$stud->name', '$alternate_names', '$stud->species', '$stud->gender','$stud->house', '$stud->dateOfBirth', '$yearOfBirth', '$wizard', '$stud->ancestry', '$stud->eyeColour', '$stud->hairColour', '$wand', '$stud->patronus', '$student', '$staff', '$stud->actor', '$alternate_actors', '$alive', '$stud->image')";
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
                            $sql = 'SELECT id, name, alternate_names, species, gender, house, dateOfBirth, yearOfBirth, wizard, ancestry, eyeColour, hairColour, wand, patronus, hogwartsStudent, hogwartsStaff, actor, alternate_actors, alive, image FROM students';
                            $result = $conn->query($sql);

                            if($result->num_rows > 0) {
                                //check data of each row
                                while($row = $result->fetch_assoc()){
                                    $decoded_row = json_decode(json_encode($row));
                                    if(strtolower($decoded_row->name) === strtolower($_POST['find'])) $output = $decoded_row;
                                }
                            }
                        }
                    ?>   
                <?php endif ?>
                <?php if($output !== null): ?>
                    <div class="container bg-blue-200 mx-auto mt-24 grid justify-items-center pb-4">
                        <div class="flex items-center">
                            <p class="font-bold text-xl uppercase m-4"><?php print_r($output->name); ?></p>
                            <?php if($output->wizard == true): ?>
                                <p class="font-semibold text-xl">- Wizard</p>
                            <?php endif ?>
                        </div>
                        <?php if($output->image ===""): ?>
                            <img class="m-4 h-64 w-33" src="https://as1.ftcdn.net/v2/jpg/04/34/72/82/1000_F_434728286_OWQQvAFoXZLdGHlObozsolNeuSxhpr84.jpg" alt="<?php print_r($output->name); ?>" />
                        <?php else: ?>
                            <img class="m-4" src="<?php echo $output->image ?>" alt="<?php print_r($output->name); ?>" />
                        <?php endif ?>
                        <ul class="list-none grid justify-items-center">
                            <?php if($output->species !=="" || $output->species !==NULL): ?>
                                <li class="flex">
                                    <p class="font-medium text-base whitespace-pre">Species: </p>
                                    <p class="font-normal text-base"><?php print_r($output->species) ?></p>
                                </li>
                            <?php endif ?>
                            <?php if($output->gender !== "" || $output->gender !== NULL): ?>
                                <li class="flex">
                                    <p class="font-medium text-base whitespace-pre">Gender: </p>
                                    <p class="font-normal text-base"><?php print_r($output->gender) ?></p>
                                </li>
                            <?php endif ?>
                            <?php if($output->house !=="" || $output->house !==NULL): ?>
                                <li class="flex">
                                    <p class="font-medium text-base whitespace-pre">House: </p>
                                    <p class="font-normal text-base"><?php print_r($output->house) ?></p>
                                </li>
                            <?php endif ?>
                            <?php if($output->dateOfBirth !=="" || $output->dateOfBirth !==NULL): ?>
                                <li class="flex">
                                    <p class="font-medium text-base whitespace-pre">Date of birth: </p>
                                    <p class="font-normal text-base"><?php print_r($output->dateOfBirth) ?></p>
                                </li>
                            <?php endif ?>
                            <?php if($output->yearOfBirth !== 0 || $output->dateOfBirth !==NULL): ?>
                                <li class="flex">
                                    <p class="font-medium text-base whitespace-pre">Year of birth: </p>
                                    <p class="font-normal text-base"><?php print_r($output->yearOfBirth) ?></p>
                                </li>
                            <?php endif ?>
                            <?php if($output->ancestry !== "" || $output->ancestry !==NULL): ?>
                                <li class="flex">
                                    <p class="font-medium text-base whitespace-pre">Ancestry: </p>
                                    <p class="font-normal text-base"><?php print_r($output->ancestry) ?></p>
                                </li>
                            <?php endif ?>
                            <?php if($output->eyeColour !== "" || $output->eyeColour !==NULL): ?>
                                <li class="flex">
                                    <p class="font-medium text-base whitespace-pre">Eye colour: </p>
                                    <p class="font-normal text-base"><?php print_r($output->eyeColour) ?></p>
                                </li>
                            <?php endif ?>
                            <?php if($output->hairColour !== "" || $output->hairColour !==NULL): ?>
                                <li class="flex">
                                    <p class="font-medium text-base whitespace-pre">Hair colour: </p>
                                    <p class="font-normal text-base"><?php print_r($output->hairColour)?></p>
                                </li>
                            <?php endif ?>
                            

                            <?php if($output->alternate_names !== "[]"): ?>
                                <li>
                                    <p class="font-medium text-base whitespace-pre">Alternate names:</p>
                                    <ul class="list-disc">
                                        <?php
                                            $aNames = explode(',', $output->alternate_names);
                                            foreach($aNames as &$alt):
                                        ?>
                                            <li class="font-light text-base ml-2"><?php echo $alt; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            <?php endif ?> 
                        </ul>
                    </div>
                    <a href="/" class="text-blue-600 visited:text-purple-600 target:shadow-lg text-xl">Go to home</a>
                <?php endif ?>
        <?php endif ?>
    </main>
	<script src="index.js"></script>
  </body>
</html>
