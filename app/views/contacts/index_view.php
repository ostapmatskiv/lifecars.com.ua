<link rel="stylesheet" type="text/css" href="<?=SERVER_URL?>assets/slick/slick.css">
<?php $this->load->js('assets/slick/slick.min.js'); 
$this->load->js_init('init__main()'); ?>



<main>
    <h1>Контакти</h1>
    <section id="contacts" class="flex m-column container">
        <div class="w50 m100 contactsText">
            <h2>Зв'яжіться з нами:</h2>
            <div>
                <p><i class="fas fa-map-marker-alt"></i> Львів, вул. Виговського, 49</p>
            </div>
            <div>
                <p><i class="fas fa-phone-alt"></i> <a href="telto:+38 096 0000 943">+38 096 0000 943</a></p>
                <p><i class="fas fa-phone-alt iNone"></i> <a href="telto:+38 093 0000 943">+38 093 0000 943</a></p>
            </div>
            <div>
                <p><i class="fas fa-envelope"></i> <a href="mailto:email@.com">email@.com</a></p>
            </div>
            <div class="contactsMap">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2574.407265598625!2d23.971396515239693!3d49.816010240756086!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x473ae772e6137b4b%3A0x83c45259538886f8!2z0LLRg9C70LjRhtGPINCG0LLQsNC90LAg0JLQuNCz0L7QstGB0YzQutC-0LPQviwgNDksINCb0YzQstGW0LIsINCb0YzQstGW0LLRgdGM0LrQsCDQvtCx0LvQsNGB0YLRjCwgNzkwMDA!5e0!3m2!1suk!2sua!4v1594740792165!5m2!1suk!2sua" width="480" height="300" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
            </div>
        </div>
        <div class="w50 m100 contactsForm">
            <h2>Залишились питання?<br>Напишіть нам:</h2>
            <form action="<?=SERVER_URL?>save/contactus" method="POST">
                <input type="text" name="name" required placeholder="Ваше ім'я:">
                <input type="email" name="mail" placeholder="E-mail:">
                <input type="tel" name="tel" required placeholder="Телефон:">
                <textarea placeholder="Ваше питання:" required  id="" cols="30" rows="8" name="mess"></textarea>
                <input type="submit" value="Надіслати">
            </form>
        </div>
    </section>
</main>