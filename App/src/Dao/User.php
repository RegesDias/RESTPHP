<?php
    namespace App\Dao;

    class User {
        private static $table = 'user';

        public static function selectId(int $id) {
            $sql = 'SELECT * FROM '.self::$table.' WHERE id = :id';
            $stmt = Conexao::Inst()->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt;
        }

        public static function selectAll() {
            $sql = 'SELECT * FROM '.self::$table;
            $stmt = Conexao::Inst()->prepare($sql);
            $stmt->execute();
            return $stmt;
        }

        public static function insert($data){
            $sql = 'INSERT INTO '.self::$table.' (email, password, name) VALUES (:em, :pa, :na)';
            $stmt = Conexao::Inst()->prepare($sql);
            $stmt->bindValue(':em', $data['email']);
            $stmt->bindValue(':pa', $data['password']);
            $stmt->bindValue(':na', $data['name']);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return 'Usuário(a) inserido com sucesso!';
            } else {
                throw new \Exception("Falha ao inserir usuário(a)!");
            }
        }

        public static function return($stmt){
            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("Nenhum usuário encontrado!");
            }
        }

        public static function login($email, $senha) {
            $sql = 'SELECT * FROM user WHERE email = :email AND senha = :senha';
            $stmt = Conexao::Inst()->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':senha', MD5($senha));
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt;
            } else {
                throw new \Exception("Usuário ou senha incorretos");
            }
        }

    }