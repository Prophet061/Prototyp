<? $this->title = 'Logowanie'; ?>

<form class="col-12 col-md-6 offset-md-3 mt-5" method="post" action="">
    <h1 class="h3 mb-3 fw-normal cl-almost-black">Rejestracja</h1>

    <div class="form-floating mb-3">
      <input type="text" class="form-control" required name="username" placeholder="">
      <label for="floatingInput">Nazwa użytkownika</label>
    </div>
    <div class="form-floating mb-3">
      <input type="text" class="form-control" required name="firstname" placeholder="">
      <label for="floatingInput">Imię</label>
    </div>
    <div class="form-floating mb-3">
      <input type="password" class="form-control" required name="password" placeholder="">
      <label for="floatingPassword">Hasło</label>
    </div>

    <button class="w-100 btn btn-lg btn-primary" type="submit">Utwórz konto</button>
</form>
