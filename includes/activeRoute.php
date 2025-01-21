<?php
function isActiveRoute($route)
{
  $current_page = $_SERVER['REQUEST_URI'];
  $current_page = trim($current_page, '/');
  $route = trim($route, '/');

  if ($route === '' && $current_page === '') {
    return true;
  }

  return strpos($current_page, $route) === 0;
}

function hasActiveSubMenu($routes)
{
  foreach ($routes as $route) {
    if (isActiveRoute($route)) {
      return true;
    }
  }
  return false;
}
