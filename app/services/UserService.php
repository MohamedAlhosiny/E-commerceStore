<?php
namespace App\Services;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserService {


    public function __construct(private UserRepositoryInterface $userRepository) // property poromotion in constructor
    {}



    public function register(array $data) {
        $data['password'] = Hash::make($data['password']);
        return $this -> userRepository -> store($data);
    }



    public function login(string $email) {

        $user = $this -> userRepository -> findByEmail($email ) ;

        if (!$user || !Hash::check(request('password'), $user->password)) { // to check the password
            return [
                'success' => false ,
                'message' => 'Invalid credentials'

            ];
        }

     $token = $user->createToken('myToken' , ['role:user'])->plainTextToken;
        return [
            'success' => true ,
            'data' => [
                'name' => $user->name ,
                'email' => $user->email,
                'token' => $token
            ] ,
            'message' => 'Login successful'
        ];


    }



    public function logout() {
         $user = Auth::user();
        $user->currentAccessToken()->delete();

        return [
            'success' => true ,
            'message' => 'Logout successful'
        ];
    }

   public function index() {
        return $this -> userRepository -> getAll();
    }

    public function show($id) {
        $getUser = $this -> userRepository -> findById($id);
        if (!$getUser) {
           return null;
        }
        return $getUser;
    }


    public function destroy($id) {
        $user = $this -> userRepository -> findById($id);
        if (!$user) {
            return false;
        }

        return $this -> userRepository -> delete($id);
    }


}
