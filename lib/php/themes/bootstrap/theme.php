<?php
// lib/php/themes/bootstrap/theme.php 20150101 - 20170317
// Copyright (C) 2015-2017 Mark Constable <markc@renta.net> (AGPL-3.0)

class Themes_Bootstrap_Theme extends Theme
{
    public function css() : string
    {
error_log(__METHOD__);

        return '
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
    <style>
body{min-height:75rem;padding-top:5rem;}
table,form{width:100%;}
table.dataTable{border-collapse: collapse !important;}

.media {
  flex-direction: column;
  align-items: center;
  margin-top: 1.5rem;
  margin-bottom: 1.5rem;
}
.media-img {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 0.75rem;
}
.media img {
  max-width: 100%;
  height: auto;
  /*margin-bottom: 0.75rem;*/
}
.media-blank {
  width: 300px;
}
.media-title {
  margin-bottom: 0.75rem;
}
.alert pre {
  margin: 0;
}
  .columns {column-gap:1.5em;columns:1;}
/*body{ background:yellow; }*/

@media (min-width:576px) {
  /*body{ background:red; }*/
}
@media (min-width:768px) {
  /*body{ background:blue; }*/
  .columns {column-gap:1.5em;columns:2;}

  .media {
    flex-direction: row;
    align-items: flex-start;
  }
  .media-body {
    margin-left: 1.5rem;
  }
  .media-img, .media-blank, .media img {
    max-width: 200px;
  }
}
@media (min-width:992px) {
  /*body{ background: green; }*/
  .media-img, .media-blank, .media img {
    max-width: 100%;
  }
}
@media (min-width:1200px) {
  /*body{ background: white; }*/
  .columns {column-gap:1.5em;columns:3;}
  .media-title {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: flex-end;
  }
}
    </style>';
    }

    public function log() : string
    {
error_log(__METHOD__);

        list($lvl, $msg) = util::log();
        return $msg ? '
        <div class="col-12">
          <div class="alert alert-' . $lvl . ' alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>' . $msg . '
          </div>
        </div>' : '';
    }

    public function head() : string
    {
error_log(__METHOD__);

        return '
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
      <div class=container>
        <a class="navbar-brand" href="' . $this->g->cfg['self'] . '" title="Home Page">
          <b><i class="fa fa-server fa-fw"></i> ' . $this->g->out['head'] . '</b>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsDefault" aria-controls="navbarsDefault" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsDefault">
          <ul class="navbar-nav mr-auto">' . $this->g->out['nav1'] . '
          </ul>
        <ul class="navbar-nav">
          <li class="nav-item ml-auto">' . $this->g->out['nav3'] . '
          </li>
        </ul>
        </div>
      </div>
    </nav>';
    }

    public function nav1(array $a = []) : string
    {
error_log(__METHOD__);

        $a = isset($a[0]) ? $a : util::get_nav($this->g->nav1);
        $o = '?o=' . $this->g->in['o'];
        $t = '?t=' . util::ses('t');
        return join('', array_map(function ($n) use ($o, $t) {
            if (is_array($n[1])) return $this->nav_dropdown($n);
            $c = $o === $n[1] || $t === $n[1] ? ' active' : '';
            $i = isset($n[2]) ? '<i class="' . $n[2] . '"></i> ' : '';
            return '
          <li class="nav-item' . $c . '"><a class="nav-link" href="' . $n[1] . '">' . $i . $n[0] . '</a></li>';
        }, $a));
    }

    public function nav2() : string
    {
error_log(__METHOD__);

        return $this->nav_dropdown(['Theme', $this->g->nav2, 'fa fa-th fa-fw']);
    }

    public function nav3() : string
    {
error_log(__METHOD__);

        if (util::is_usr()) {
            $usr[] = ['Change Profile', '?o=accounts&m=update&i=' . $_SESSION['usr']['id'], 'fas fa-user fa-fw'];
            $usr[] = ['Change Password', '?o=auth&m=update&i=' . $_SESSION['usr']['id'], 'fas fa-key fa-fw'];
            $usr[] = ['Sign out', '?o=auth&m=delete', 'fas fa-sign-out-alt fa-fw'];

            if (util::is_adm() && !util::is_acl(0)) $usr[] =
                ['Switch to sysadm', '?o=accounts&m=switch_user&i=' . $_SESSION['adm'], 'fas fa-user fa-fw'];

            return $this->nav_dropdown([$_SESSION['usr']['login'], $usr, 'fas fa-user fa-fw']);
        } else return '';
    }

    public function nav_dropdown(array $a = []) : string
    {
error_log(__METHOD__);

        $o = '?o=' . $this->g->in['o'];
        $i = isset($a[2]) ? '<i class="' . $a[2] . '"></i> ' : '';
        return '
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $i . $a[0] . '</a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">'.join('', array_map(function ($n) use ($o) {
            $c = $o === $n[1] ? ' active' : '';
            $i = isset($n[2]) ? '<i class="' . $n[2] . '"></i> ' : '';
            return '
              <a class="dropdown-item" href="' . $n[1] . '">' . $i . $n[0] . '</a>';
        }, $a[1])).'
            </div>
          </li>';
    }

    public function main() : string
    {
error_log(__METHOD__);

        return '
    <main class="container">
      <div class="row">' . $this->g->out['log'] . $this->g->out['main'] . '
      </div>
    </main>';
    }

    public function js() : string
    {
error_log(__METHOD__);

        return '
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://use.fontawesome.com/releases/v5.0.8/js/solid.js" integrity="sha384-+Ga2s7YBbhOD6nie0DzrZpJes+b2K1xkpKxTFFcx59QmVPaSA8c7pycsNaFwUK6l" crossorigin="anonymous"></script>
    <script src="https://use.fontawesome.com/releases/v5.0.8/js/fontawesome.js" integrity="sha384-7ox8Q2yzO/uWircfojVuCQOZl+ZZBg2D2J5nkpLqzH1HY0C1dHlTKIbpRz/LG23c" crossorigin="anonymous"></script>
';
    }

    protected function pager(array $ary) : string
    {
error_log(__METHOD__);

        extract($ary);

        $b = '';
        $o = util::ses('o');

        for($i = 1; $i <= $last; $i++) $b .= '
              <li class="page-item' . ($i === $curr ? ' active' : '') . '">
                <a class="page-link" href="?o=' . $o . '&m=list&p=' . $i . '">' . $i . '</a>
              </li>';

        return '
          <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm pull-right">
              <li class="page-item' . ($curr === 1 ? ' disabled' : '') . '">
                <a class="page-link" href="?o=' . $o . '&m=list&p=' . $prev . '" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
                  <span class="sr-only">Previous</span>
                </a>
              </li>' . $b . '
              <li class="page-item' . ($curr === $last ? ' disabled' : '') . '">
                <a class="page-link" href="?o=' . $o . '&m=list&p=' . $next . '" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                  <span class="sr-only">Next</span>
                </a>
              </li>
            </ul>
          </nav>';
    }
}

?>
