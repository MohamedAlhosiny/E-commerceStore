<?php
namespace App\Services;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserService {


    public function __construct(private UserRepositoryInterface $userRepository)
    {
        $this -> userRepository = $userRepository;
    }

    public function register(array $data) {
        $data['password'] = Hash::make($data['password']);
        return $this -> userRepository -> store($data);
    }



    public function login(array $data) {

        $user = $this -> userRepository -> findByEmail($data) ;

        if (!$user || !Hash::check($data['password'] , $user->password)) {
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
        return $this -> userRepository -> index();
    }

    public function show($id) {
        $getUser = $this -> userRepository -> show($id);
        if (!$getUser) {
           return null;
        }
        return $getUser;
    }


    public function destroy($id) {
        $user = $this -> userRepository -> show($id);
        if (!$user) {
            return false;
        }

        return $this -> userRepository -> destroy($id);
    }


}
