<?php require_once('common/header.php'); ?>
  <section role="main" id="dashboard">
    <div class="container-fluid">
      <div class="row row-1">
        <div class="col-xs-12 col-lg-4">
          <div class="row">
            <div class="col-sm-6 col-lg-12">
              <div id="number-of-games-box" class="box">
                <div class="icon">
                  <i class="glyphicon glyphicon-scale"></i>
                </div>
                <h4>Number of Games</h4>
                <strong id="number-of-games-value">0</strong>
              </div>
            </div>
            <div class="col-sm-6 col-lg-12">
              <div id="favorite-system-box" class="box">
                <div class="icon">
                  <i class="fa fa-television"></i>
                </div>
                <h4>Favorite System</h4>
                <strong><i class="fa fa-spinner fa-spin"></i></strong>
                <span id="favorite-system-games"></span>
              </div>
            </div> 
          </div> <!-- // .row -->
        </div> <!-- // col-xs-12 col-lg-4 -->
        <div class="col-xs-12 col-lg-4">
          <div class="row">
            <div class="col-sm-6 col-lg-12">
              <div id="number-of-systems-box" class="box">
                <div class="icon">
                  <i class="fa fa-keyboard-o"></i>
                </div>
                <h4>Number of Systems</h4>
                <strong id="number-of-systems-value">0</strong>
              </div><!-- #number-of-systems -->
            </div>
            <div class="col-sm-6 col-lg-12">
              <div id="favorite-genre-box" class="box">
                <div class="icon">
                  <i class="fa fa-bookmark"></i>
                </div>
                <h4>Favorite Genre</h4>
                <strong><i class="fa fa-spinner fa-spin"></i></strong>
                <span id="favorite-genre-games"></span>
              </div>
            </div> 
          </div> <!-- // .row -->
        </div> <!-- // col-xs-12 col-lg-4 -->
        <div class="col-xs-12 col-lg-4">
          <div class="row">
            <div class="col-sm-6 col-lg-12">
              <div id="number-of-games-completed-box" class="box">
                <div class="icon">
                  <i class="fa fa-trophy"></i>
                </div>
                <h4>Games Beaten</h4>
                <strong id="number-of-games-completed-value">0</strong>
                <span id="percentage-games-beaten">
                  <span></span>
                  <i class="fa fa-star"></i>
                  <i class="fa fa-star"></i>
                  <i class="fa fa-star"></i>
                  <i class="fa fa-star"></i>
                  <i class="fa fa-star"></i>
                </span>
              </div> <!-- #number-of-games-completed -->
            </div>
            <div class="col-sm-6 col-lg-12">
              <div id="last-game-added-box" class="box">
                <div class="icon">
                  <i class="fa fa-plus"></i>
                </div>
                <h4>Last Game Added</h4>
                <strong><i class="fa fa-spinner fa-spin"></i></strong>
                <span id="last-game-added-date"></span>
              </div><!-- #last-game-added -->
            </div> 
          </div> <!-- // .row -->
        </div> <!-- // col-xs-12 col-lg-4 -->
      </div> <!-- //.row-1 -->
      <div class="row row-2">
        <div class="col-xs-12">
          <div class="row">
            <div class="col-xs-12 col-lg-6">
              <div id="system-breakdown-box" class="box">
                <h4>Top Systems Breakdown<i class="fa fa-spinner fa-spin"></i></h4>
                <div id="system-breakdown"></div>
              </div>
            </div>
            <div class="col-xs-12 col-lg-6">
              <div id="genre-breakdown-box" class="box">
                <h4>Top Genres Breakdown<i class="fa fa-spinner fa-spin"></i></h4>
                <div id="genre-breakdown"></div>
              </div>
            </div>
          </div> <!-- // .row -->
        </div> <!-- // col-xs-12 -->
      </div> <!-- // .row-2 -->
      <div class="row row-3">
        <div class="col-xs-12">
          <div id="video-game-timeline-box" class="box">
            <h4>Video Game Timeline<i class="fa fa-spinner fa-spin"></i></h4>
            <div id="video-game-timeline"></div>
          </div>
        </div> <!-- // col-xs-12 -->
      </div> <!-- // .row-3 -->
    </div> <!-- // .container-fluid -->
  </section> <!-- // #main -->
<?php require_once('common/footer.php'); ?>
