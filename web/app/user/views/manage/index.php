<?php
$this->headtitle = "Account Settings";
$this->headlink = "<link rel=\"stylesheet\" href=\"/_user/css/manage.css\" type=\"text/css\" media=\"all\" />";
?>
<div class="usermanage">

<div class="profile">
  <h2>Profile</h2>
  <div class="info">
    <img src="<?php echo $this->user['photo_path']?>/w100.png" />
    <ul>
      <li><b><?php echo $this->session->uname?></b></li>
      <li></li>
      <li><a href="/user/profile-manage/">Edit profile</a>  - <a class="light" href="/user/profile/<?php echo $this->session->uname?>" target="_blank">Preview</a></li>
      <li><a href="/user/profile-manage/photo">Change photo</a></li>
    </ul>
  </div>
</div>

<div class="personal">
  <h2>Personal Settings</h2>
  <table> 
    <tr> 
      <td class="headings">Security</td> 
      <td> 
        <ul> 
          <li><a href="/user/account-manage/pwd">Change password</a></li>
        </ul>
      </td> 
    </tr> 
    <tr> 
      <td class="headings">Email addresses</td> 
      <td>
        <ul> 
          <li><?php echo $this->user['email']?></li> 
          <li><a href="/user/account-manage/email">Edit</a></li> 
        </ul>
      </td>
    </tr> 
  </table> 
</div>

<div class="products">
  <h2>My products</h2>
  <ul> 
    <li> 
      <img src="/_default/img/application.png" alt="App" /> 
      <span> 
        <a href="#">Blog</a> -
        <a href="#" class="light">Settings</a>&nbsp;
        <a href="#" class="light">Add content</a>&nbsp;
      </span> 
    </li>
    <li> 
      <img src="/_default/img/application.png" alt="App" /> 
      <span> 
        <a href="#">Google Photo</a> -
        <a href="#" class="light">Manage</a>
      </span> 
    </li>
    <li> 
      <img src="/_default/img/application.png" alt="App" /> 
      <span> 
        <a href="#">Bookmark</a> -
        <a href="#" class="light">Manage</a>
      </span> 
    </li>
    <li> 
      <img src="/_default/img/application.png" alt="App" /> 
      <span> 
        <a href="#">Wiki</a> -
        <a href="#" class="light">Manage</a>
      </span> 
    </li>
    <li> 
      <img src="/_default/img/application.png" alt="App" /> 
      <span> 
        <a href="#">Page</a> -
        <a href="#" class="light">Manage</a>&nbsp;
        <a href="#" class="light">Add page</a>&nbsp;
      </span> 
    </li>
    <li> 
      <img src="/_default/img/application.png" alt="App" /> 
      <span> 
        <a href="#">BBS</a> -
        <a href="#" class="light">Manage</a>
      </span> 
    </li>
    <li> 
      <img src="/_default/img/application.png" alt="App" /> 
      <span> 
        <a href="#">Reader</a> -
        <a href="#" class="light">Manage</a>
      </span> 
    </li>
    <li> 
      <img src="/_default/img/application.png" alt="App" /> 
      <span> 
        <a href="#">Google Calendar</a> -
        <a href="#" class="light">Manage</a>
      </span> 
    </li>
    <li> 
      <img src="/_default/img/application.png" alt="App" /> 
      <span> 
        <a href="#">Google Doc</a> -
        <a href="#" class="light">Manage</a>
      </span> 
    </li>
  </ul>       
</div>
<div class="products">
  <h2>Try something new</h2>
  <ul>
    <li> 
      <img src="/_default/img/application.png" alt="App" /> 
      <span> 
        <a href="#">KB</a> -
        <a href="#" class="light">Manage</a>
      </span> 
    </li>
    <li> 
      <img src="/_default/img/application.png" alt="App" /> 
      <span> 
        <a href="#">Issue Tracker</a> -
        <a href="#" class="light">Manage</a>
      </span> 
    </li>
  </ul> 
</div>
</div>
