<table width="100%" border="1">
  <tr>
    <td>ID</td>
    <td>Name</td>
    <td>Title</td>
    <td>Apps</td>
    <td>Type</td>
    <td>User Mode</td>
    <td>Updated</td>
  </tr>
<?php
foreach ($this->feed as $entry) {
    echo "  <tr>
    <td>{$entry['id']}</td>
    <td>{$entry['name']}</td>
    <td>{$entry['title']}</td>
    <td>{$entry['apps']}</td>
    <td>{$entry['type']}</td>
    <td>{$entry['user']}</td>
    <td>{$entry['updated']}</td>
  </tr>";
}
?>
</table>
