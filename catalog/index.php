<?php require_once('../common/header.php'); ?>
  <section role="main" id="catalog">
    <div class="container-fluid">
      <div class="alert alert-info alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        Data is refreshed every one-hour.  Go ahead and give it a try!
      </div>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="loader">
              <div class="loader-inner ball-pulse-sync">
                <div></div>
                <div></div>
                <div></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php require_once('../common/footer.php'); ?>
