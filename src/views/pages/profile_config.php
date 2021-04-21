<?=$render('header', ['loggedUser' => $loggedUser]);?>

<section class="container main">
    <?=$render('sidebar', ['activeMenu' => 'config']);?>

    <section class="feed mt-10">

        <div class="row">
            <div class="column pr-5">

                <h1>Configurações <?=$searchTerm;?></h1>

                <div class="config-form">

                    <form class ="form-config" method="POST" action="<?=$base;?>/config">

                        <?php if(!empty($flash)): ?>
                            <div class="flash-error"><?php echo $flash; ?></div>
                        <?php endif ?>
                        
                        <div class="fild-avatar">
                            <div>Novo Avatar</div>
                            <div>
                                <input type="file">
                            </div>
                        </div>
                        <div class="fild-cover">
                            <div>Nova Capa</div>
                            <div>
                                <input type="file">
                            </div>
                        </div>

                        <hr>

                        <div class="fild-name">
                            <div>Nome Completo:</div>
                            <div class="input-fild-name">
                                <input class="input" type="text" name="name" value="<?=$user->name;?>">
                            </div>
                        </div>

                        <div class="fild-birthdate">
                            <div>Data de Nascimento:</div>
                            <div class="input-fild-birthdate">
                                <input class="input" type="text" name="birthdate" id="birthdate" value="<?=date('d/m/Y', strtotime($user->birthdate));?>">
                            </div>
                        </div>

                        <div class="fild-email">
                            <div>E-mail:</div>
                            <div class="input-fild-email">
                                <input class="input" type="email" name="email" value="<?=$user->email;?>">
                            </div>
                        </div>

                        <div class="fild-city">
                            <div>Cidade:</div>
                            <div class="input-fild-city">
                                <input class="input" type="text" name="city" value="<?=$user->city;?>">
                            </div>
                        </div>

                        <div class="fild-work">
                            <div>Trabalho:</div>
                            <div class="input-fild-work">
                                <input class="input" type="text" name="work" value="<?=$user->work;?>">
                            </div>
                        </div>

                        <hr>

                        <div class="fild-password">
                            <div>Nova Senha:</div>
                            <div class="input-fild-password">
                                <input class="input" type="password" name="password">
                            </div>
                        </div>

                        <div class="fild-repeat-password">
                            <div>Repita Senha:</div>
                            <div class="input-fild-repeat-password">
                                <input class="input" type="password" name="repeat-password">
                            </div>
                        </div>

                        <input type="hidden" name="id" value="<?=$user->id;?>">
                        <input type="submit" class="button" value="Salvar">

                    </form>
                    
                </div>

            </div>
            <div class="column side pl-5">
                <?=$render('right-side');?>
            </div>
        </div>

    </section>

</section>
<script src="https://unpkg.com/imask"></script>
<script>
    IMask(
        document.getElementById('birthdate'),
        {
            mask:'00/00/0000'
        }
    );
</script>

<?=$render('footer');?>