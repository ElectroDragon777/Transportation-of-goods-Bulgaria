<?php

namespace App\Models;
use Core\Model;

class User extends Model{

    var $primaryKey = 'id';
    var $table = 'users';
    
    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'first_name', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'second_name', 'type' => 'varchar', 'default' => ''),
        array('name' => 'phone', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'email ', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'address', 'type' => 'varchar', 'default' => ''),
        array('name' => 'city', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'state', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'password', 'type' => 'varchar', 'default' => ''),
        array('name' => 'last_login', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'type', 'type' => 'varchar', 'default' => ':NULL')
    );
    
    // Create new user
    public function createUser($data) {
        $data['password'] = Hash::make($data['password']);  // Hash the password before saving
        return $this->create($data);
    }

    // Read user (by ID)
    public function getUserById($id) {
        return $this->find($id);
    }

    // Update user (by ID)
    public function updateUser($id, $data) {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);  // Hash the new password before saving
        }
        return $this->update($id, $data);
    }

    // Delete user (by ID)
    public function deleteUser($id) {
        return $this->delete($id);
    }
    
    // Authenticate user
    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && Hash::check($password, $user['password'])) {
            // User authenticated successfully
            return $user;
        }
        return false;  // Authentication failed
    }

    // Find user by email
    public function findByEmail($email) {
        return $this->where('email', '=', $email)->first();
    }
    
    // == Functions: ==
    // Request password reset
    public function requestPasswordReset($email) {
        $user = $this->findByEmail($email);
        if ($user) {
            // Generate a reset token and save it
            $resetToken = bin2hex(random_bytes(16));
            $this->update($user['id'], ['reset_token' => $resetToken, 'reset_requested_at' => time()]);
            // Send the reset token to user's email
            // (Implementation of email sending is not shown here)
        }
    }

    // Reset password
    public function resetPassword($token, $newPassword) {
        $user = $this->where('reset_token', '=', $token)->first();
        if ($user && (time() - $user['reset_requested_at']) < 3600) {  // Token is valid for 1 hour
            $this->update($user['id'], ['password' => Hash::make($newPassword), 'reset_token' => null, 'reset_requested_at' => null]);
            return true;
        }
        return false;
    }
    
    // == User Roles ==
    // Check if user has a specific role
    public function hasRole($role) {
        return $this->type === $role;
    }

    // Get all users with a specific role
    public function getUsersByRole($role) {
        return $this->where('type', '=', $role)->get();
    }
}
