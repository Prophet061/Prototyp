<? $this->title = 'Logowanie'; ?>

<form class="col-12 col-md-4 offset-md-4">
    <h1 class="mb-5"><?= Yii::$app->params['appname'] ?></h1>

    <div class="form-floating mb-3">
      <input type="text" class="form-control" name="username" placeholder="">
      <label for="floatingInput">Nazwa użytkownika</label>
    </div>
    <div class="form-floating mb-5">
      <input type="password" class="form-control" name="password" placeholder="">
      <label for="floatingPassword">Hasło</label>
    </div>

    <button class="w-100 btn btn-lg btn-primary" type="submit">Zaloguj</button>
  </form>
