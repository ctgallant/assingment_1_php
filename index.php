<?php 

$dsn = 'mysql:host=localhost;dbname=todolist';
    $username = 'host';
    //$password = 'password';

    try {
        $db = new PDO($dsn, $username);
        //$db = new PDO($dsn, $username, $password);
    } 
    catch (PDOException $e) {
        $error_message = "Database Error: ";
        $error_message .= $e->getMessage();
        include('error.php');
        
    exit();
    }

    $query = 'SELECT * FROM todoitems ORDER BY ItemNum';
    $statement = $db->prepare($query);
    $statement->execute();
    $todoitems = $statement->fetchAll();
    $statement->closeCursor();

    $item_num = filter_input(INPUT_POST, 'item_num', FILTER_VALIDATE_INT);

    if ($item_num) {
        $query = 'DELETE FROM todoitems 
                  WHERE ItemNum = :item_num';
        $statement = $db->prepare($query);
        $statement->bindValue(':item_num', $item_num);
        $success = $statement->execute();
        $statement->closeCursor(); 
    }  

    $query = 'INSERT INTO todoitems 
    (Title, Description)
            VALUES
    (:title, :descr)';
    $statement = $db->prepare($query);
    $statement->bindValue(':title', $title);
    $statement->bindValue(':descr', $description);
    $statement->execute();
    $statement->closeCursor();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo List Assignment</title> 
</head>

<body>
    <h1>ToDo List</h1>
<div>
    <?php if(!empty($todoitems)) { ?>
    <?php foreach ($todoitems as $item) : ?>
</div>

<div>
    <p><?php echo $item['Title']; ?></p>
    <p><?php echo $item['Description']; ?></p>
</div>

<div>
    <form method="post">
        <input type="hidden" name="item_num" value="<?php echo $item['ItemNum']; ?>">
        <button>❌</button>
    </form>    
</div>

<div>
    <form method="post">
            <label>Title:</label>
                <input type="text" name="title" maxlength="20" placeholder="Title" required>
            <label>Description:</label>
                <input type="text" name="description" maxlength="50" placeholder="Description" required>
            <button class="add-button bold">Add<br>Item</button>
    </form>   
</div>
</body>
</html>
