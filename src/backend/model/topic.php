<?php
//require('./session.php');

class Topic
{
  private $name;
  private $sessions = [];

  /**
   * Constructor for Topic class
   *
   * @param string $name
   */
  function __construct($name)
  {
    $this->name = $name;
  }

  /**
   * get the name of the topic
   *
   * @return string
   */
  function getName(): string
  {
    return $this->name;
  }

  /**
   * get the array of sessions 
   *
   * @return array
   */
  function getSessions(): array
  {
    return $this->sessions;
  }

  /**
   * add Session to array of sessions
   *
   * @param Session class instance
   * @return void
   */
  function addSession(Session $session): void
  {
    $this->sessions[] = $session;
  }

  function jsonSerialize()
  {
    if (count($this->sessions) > 0) {
      $arraySesion = [];
      foreach ($this->sessions as $sesion) {
        $arraySesion[] = $sesion->jsonSerialize();
      }
    }

    return
      [
        'name'   => $this->getName(),
        'sesion' => $arraySesion
      ];
  }
}
