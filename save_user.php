<?php
   class Start{
		
		public $db;
		
		function __construct( ){
			$this->db = mysqli_connect('localhost', 'stud', 'password', 'version1');
		}
		
		function user(){
			//заносим введенные пользователем данные в переменную, если он данные пустые, то уничтожаем переменную
			if (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);} } 
			if (isset($_POST['password'])) { $password=$_POST['password']; if ($password =='') { unset($password);} }
			if (isset($_POST['name'])) { $name=$_POST['name']; if ($name =='') { unset($name);} }
			if (isset($_POST['surname'])) { $surname=$_POST['surname']; if ($surname =='') { unset($surname);} }
			if (isset($_POST['city'])) { $city=$_POST['city']; if ($city =='') { unset($city);} }
			
			//если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
			if (empty($login) or empty($password)) {
				exit ("Вы ввели не всю информацию, вернитесь назад и заполните все поля!");
			}
			
			
			//если логин и пароль введены, то обрабатываем их, чтобы теги и скрипты не работали, мало ли что люди могут ввести
			$login = stripslashes($login);
			$login = htmlspecialchars($login);
			
			$password = stripslashes($password);
			$password = htmlspecialchars($password);
			
			$name = stripslashes($name);
			$name = htmlspecialchars($name);
			
			$surname = stripslashes($surname);
			$surname = htmlspecialchars($surname);
			
			$city = stripslashes($city);
			$city = htmlspecialchars($city);
			
			//удаляем лишние пробелы
			$login = trim($login);
			$password = trim($password);
			$name = trim($name);
			$surname = trim($surname);
			$city = trim($city);
			
			//ищем ид для того что-бы сделать все логины уникальными и сказать если он не уникальный
			$result = mysqli_query($this->db,"SELECT id FROM users WHERE login='$login'");
			$myrow = mysqli_fetch_array($result);
			if (!empty($myrow['id'])) {
				exit ("Извините, введённый вами логин уже зарегистрирован. Введите другой логин.");
			}
			
					
			//обрабатываем фотографию
			if ($_FILES && $_FILES['filename']['error']== UPLOAD_ERR_OK)
			{
				//извлекаем название фотографии
				$name_img = $_FILES['filename']['name'];
				
				//делаем название фотограффи уникальным для этого добавляем к названию впереди логин(логин уникальный)
				$uniq_name_img = $login.$name_img;
				
				//загружаем файл в ту же директорию
				move_uploaded_file($_FILES['filename']['tmp_name'],$uniq_name_img);
			}
			
			//записываем данные в табл
			$result_add_in_table_users = mysqli_query ($this->db,"INSERT INTO users (login,password) VALUES('$login','$password')");
			$result_add_in_table_user = mysqli_query ($this->db,"INSERT INTO user (name,surname,img,city) VALUES('$name','$surname','$uniq_name_img','$city')");
			
			// Проверяем, есть ли ошибки
			if ($result_add_in_table_users=='TRUE' && $result_add_in_table_user=='TRUE')
			{
				echo "Вы успешно зарегистрированы! Теперь вы можете зайти на сайт. <a href='index.php'>Главная страница</a>";
			}
			else {
				echo "Ошибка! Вы не зарегистрированы.";
			}
			
		}
			
	}
	
  
	$obj_class_Start =  new Start();
	$obj_class_Start->user();
	
    ?>