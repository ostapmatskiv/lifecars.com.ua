<link rel="stylesheet" type="text/css" href="<?=SERVER_URL?>assets/slick/slick.css">
<?php $this->load->js('assets/slick/slick.min.js'); 
$this->load->js_init('init__main()'); ?>

<main>
    <h1><?=$_SESSION['alias']->name?></h1>
    <section id="contacts" class="flex m-column container">
        <div class="w50 m100 contactsText">
            <h2>Зв'яжіться з нами:</h2>
            <div>
                <p><i class="fas fa-map-marker-alt"></i> <?=$this->text('м. Львів, вул. Виговського, 49')?></p>
            </div>
            <div>
                <p><i class="fas fa-phone-alt"></i> <a href="telto:+38 096 0000 943">+38 096 0000 943</a></p>
                <p><i class="fas fa-phone-alt iNone"></i> <a href="telto:+38 093 0000 943">+38 093 0000 943</a></p>
            </div>
            <div>
                <p><i class="fas fa-envelope"></i> <a href="mailto:manager@lifecars.com.ua">manager@lifecars.com.ua</a></p>
            </div>
            <div class="contactsMap">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d5148.814876012718!2d23.973585!3d49.816007!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xa5abad275ae2f1ff!2sLifeCars!5e0!3m2!1suk!2sua!4v1596440989263!5m2!1suk!2sua" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
            </div>
        </div>
        <div class="w50 m100 contactsForm">
            <h2>Залишились питання?<br>Напишіть нам:</h2>
            <form action="<?=SERVER_URL?>save/contactus" method="POST">
                <input type="text" name="name" required placeholder="Ваше ім'я:">
                <input type="email" name="mail" placeholder="E-mail:">
                <input type="tel" name="tel" required placeholder="Телефон:">
                <textarea placeholder="Ваше питання:" required  id="" cols="30" rows="8" name="mess"></textarea>
                <?php if(!$this->userIs()) {
                    echo '<div class="flex h-center">';
                    $this->load->library('recaptcha');
                    $this->recaptcha->form();
                    echo "</div>";
                } ?>
                <input type="submit" value="Надіслати">
            </form>
        </div>
    </section>
</main>