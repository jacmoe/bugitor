<?php

class VersioncontrolGitAccount extends VersioncontrolAccount {

  /**
   * Overwrite.
   */
  public function isUsernameValid(&$username) {
    // @TODO: adjust, too
    // We want to allow "prename name <email@example.org>"
    // Or just "nick <email@example.org>"
    // Or just whatever naming convention you like
    // This means, we just check for control characters and NULs here
    if (preg_match("/[\\x00-\\x1f]/", $username) == 0) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Overwrite
   */
  public function usernameSuggestion($user) {
    // @TODO: adjust later
    // Use the kindof standard git identifier here, it's nothing too serious.
    return $user->name ." <$user->mail>";
  }

}
