<?php

class User extends Database
{

    private $id;
    private $email;
    private $motdepasse;
    private $avenir;
    private $lesphotos;
    private $flashback;
    private $admin;

    function __construct()
    {
        parent::__construct();
    }

    public function addUser()
    {
        $motdepasse = md5($this->motdepasse);

        $stmt = $this->dbh->prepare("INSERT INTO user VALUES (null,?,?,?,?,?,?)");

        $stmt->bindParam(1, $this->email);
        $stmt->bindParam(2, $motdepasse);
        $stmt->bindParam(3, $this->avenir);
        $stmt->bindParam(4, $this->lesphotos);
        $stmt->bindParam(5, $this->flashback);
        $stmt->bindParam(6, $this->admin);

        $stmt->execute();

        $this->id = $this->dbh->lastInsertId();
    }

    public function updateUserWithoutPassword()
    {
        if ($this->isSuperAdminLogin()) {
            $stmt = $this->dbh->prepare("UPDATE user
            SET email = ?, avenir = ?, lesphotos = ?, flashback = ?, admin = ?
            WHERE id = ?");

            $stmt->bindParam(1, $this->email);
            $stmt->bindParam(2, $this->avenir);
            $stmt->bindParam(3, $this->lesphotos);
            $stmt->bindParam(4, $this->flashback);
            $stmt->bindParam(5, $this->admin);
            $stmt->bindParam(6, $this->id);

            $count = $stmt->execute();

            if ($count > 0) {
                return true;
            }
        }
        return false;
    }

    public function isSuperAdminLogin()
    {
        if ($this->isSuperAdmin()) {
            if ($this->email == "frederic.guilbert@univ-catholille.fr" && $_SESSION["LOGIN"]["email"] == "frederic.guilbert@univ-catholille.fr") {
                $this->admin = "1";
                return true;
            }

            if ($this->email == "coralie.talma@univ-catholille.fr" && $_SESSION["LOGIN"]["email"] == "coralie.talma@univ-catholille.fr") {
                $this->admin = "1";
                return true;
            }

            return false;
        }

        return true;
    }

    public function isSuperAdmin()
    {
        return $this->email == "frederic.guilbert@univ-catholille.fr" || $this->email == "coralie.talma@univ-catholille.fr";
    }

    public function updateUser()
    {
        if ($this->isSuperAdminLogin()) {
            $motdepasse = md5($this->motdepasse);

            $stmt = $this->dbh->prepare("UPDATE user
            SET email = ?, motdepasse = ?, avenir = ?, lesphotos = ?, flashback = ?, admin = ?
            WHERE id = ?");

            $stmt->bindParam(1, $this->email);
            $stmt->bindParam(2, $motdepasse);
            $stmt->bindParam(3, $this->avenir);
            $stmt->bindParam(4, $this->lesphotos);
            $stmt->bindParam(5, $this->flashback);
            $stmt->bindParam(6, $this->admin);
            $stmt->bindParam(7, $this->id);

            $count = $stmt->execute();

            if ($count > 0) {
                return true;
            }
        }
        return false;
    }

    public function existUser()
    {
        $stmt = $this->dbh->prepare('select email from user where email = ?');

        $stmt->bindParam(1, $this->email);

        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function auth()
    {
        $motdepasse = md5($this->motdepasse);

        $stmt = $this->dbh->prepare(''
            . 'SELECT * '
            . 'FROM user '
            . 'WHERE email = ? and motdepasse = ?');

        $stmt->bindParam(1, $this->email);
        $stmt->bindParam(2, $motdepasse);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function getUserByEmail()
    {
        $stmt = $this->dbh->prepare('SELECT * 
                FROM user 
                WHERE email = ?');

        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function getAllUser()
    {
        $stmt = $this->dbh->prepare('SELECT * FROM user');

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    }

    public function deleteUserById()
    {
        $user = $this->getUserById();
        if (!$user) {
            return false;
        }
        $this->email = $user["email"];
        if (!$this->isSuperAdmin()) {
            $stmt = $this->dbh->prepare('DELETE FROM user
            WHERE id = ?');

            $stmt->bindParam(1, $this->id);
            $count = $stmt->execute();

            if ($count > 0) {
                return true;
            }
        }
        return false;
    }

    public function getUserById()
    {
        $stmt = $this->dbh->prepare('SELECT * 
                FROM user 
                WHERE id = ?');

        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        if (count($stmt)) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getMotdepasse()
    {
        return $this->motdepasse;
    }

    /**
     * @param mixed $motdepasse
     */
    public function setMotdepasse($motdepasse)
    {
        $this->motdepasse = $motdepasse;
    }

    function getAvenir()
    {
        return $this->avenir;
    }

    function setAvenir($avenir)
    {
        $this->avenir = $avenir;
    }

    function getLesphotos()
    {
        return $this->lesphotos;
    }

    function setLesphotos($lesphotos)
    {
        $this->lesphotos = $lesphotos;
    }

    function getFlashback()
    {
        return $this->flashback;
    }

    function setFlashback($flashback)
    {
        $this->flashback = $flashback;
    }

    function getAdmin()
    {
        return $this->admin;
    }

    function setAdmin($admin)
    {
        $this->admin = $admin;
    }

    function getId()
    {
        return $this->id;
    }

    function setId($id)
    {
        $this->id = $id;
    }

}
