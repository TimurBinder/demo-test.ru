</main>

<footer>
    <div class="container text-lg-start text-center">
        <div class="row d-lg-flex d-none">
            <?php
                $APPLICATION->IncludeComponent(
                    "bitrix:menu", 
                    "footer", 
                    array(
                        "ALLOW_MULTI_SELECT" => "N",
                        "CHILD_MENU_TYPE" => "left",
                        "DELAY" => "N",
                        "MAX_LEVEL" => "1",
                        "MENU_CACHE_GET_VARS" => array(
                        ),
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_TYPE" => "N",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "ROOT_MENU_TYPE" => "rose_town",
                        "USE_EXT" => "N",
                        "COMPONENT_TEMPLATE" => "footer"
                    ),
                    false
                );
                ?>
        </div>

        <div class="row info">
            <div class="col-lg-4 col-12 mt-lg-0 mt-3 d-flex flex-column">
                <a class="phone" href="tel:+74242733333">+7 (4242) 73 33 33</a>
                <div class="mt-5">
                    <p>г. Южно-Сахалинск</p>
                    <p>ул. Комсомольская 271 А, корпус 1</p>
                </div>
            </div>

            <div class="options-menu col-lg-3 col-12 mt-lg-0 mt-3 d-flex flex-column justify-content-between">
                <a href="#" class="mt-1">О компании</ф>
                <a href="#" class="mt-1">Отдел продаж</a>
                <a href="#" class="mt-1">Контакты</a>
                <a href="#" class="mt-1">Документы</a>
            </div>

            <div class="events col-lg-2 col-12 mt-lg-0 mt-3 d-flex flex-column">
                <a href="#" class="mt-1">Задать вопрос</a>
                <a href="#" class="mt-1">Заказать звонок</a>
            </div>

            <div class="socials col-lg-3 col-12 mt-lg-0 mt-3 d-flex justify-content-lg-end justify-content-center">
                <a href="#" class="me-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M23.45 5.948c.166-.546 0-.948-.795-.948H20.03c-.668 0-.976.347-1.143.73c0 0-1.335 3.196-3.226 5.272c-.612.602-.89.793-1.224.793c-.167 0-.418-.191-.418-.738V5.948c0-.656-.184-.948-.74-.948H9.151c-.417 0-.668.304-.668.593c0 .621.946.765 1.043 2.513v3.798c0 .833-.153.984-.487.984c-.89 0-3.055-3.211-4.34-6.885C4.45 5.288 4.198 5 3.527 5H.9c-.75 0-.9.347-.9.73c0 .682.89 4.07 4.145 8.551C6.315 17.341 9.37 19 12.153 19c1.669 0 1.875-.368 1.875-1.003v-2.313c0-.737.158-.884.687-.884c.39 0 1.057.192 2.615 1.667C19.11 18.216 19.403 19 20.405 19h2.625c.75 0 1.126-.368.91-1.096c-.238-.724-1.088-1.775-2.215-3.022c-.612-.71-1.53-1.475-1.809-1.858c-.389-.491-.278-.71 0-1.147c0 0 3.2-4.426 3.533-5.929Z" clip-rule="evenodd"/></svg>
                </a>

                <a href="#" class="me-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path fill="currentColor" d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104l.022.26l.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105l-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006l-.087-.004l-.171-.007l-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103l.003-.052l.008-.104l.022-.26l.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007l.172-.006l.086-.003l.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z"/></svg>
                </a>

                <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5Z"/></svg>
                </a>
            </div>
        </div>

        <div class="copyright row text-center">
            <p class="w-100">
                © Строительный холдинг «SSD Group»
            </p>
        </div>
    </div>
</footer>
<?php

?>
</body>

</html>