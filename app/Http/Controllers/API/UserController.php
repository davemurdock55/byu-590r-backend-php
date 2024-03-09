<?php

namespace App\Http\Controllers\API;

use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;


class UserController extends BaseController
{
	public function getUser()
	{
		$authUser = Auth::user();
		$user = User::findOrFail($authUser->id); // findOrFail gets the first object in the database where the id is equal to the id you're logged in as and return the first it finds (if it doesn't find anything, it fails)
		$user->avatar = $this->getS3Url($user->avatar); // getS3Url is combining the aws bucket url and its hash with the rest of the path (stored in "avatar") and temporarily overwriting that by doing user->avatar

		return $this->sendResponse($user, 'User');
	}

	public function uploadAvatar(Request $request)
	{

		$request->validate([
			'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
		]);


		if ($request->hasFile('image')) {

			$authUser = Auth::user();

			$user = User::findOrFail($authUser->id);

			$extension = request()->file('image')->getClientOriginalExtension();

			$image_name = time() . '_' . $authUser->id . '.' . $extension;

			$path = $request->file('image')->storeAs(
				'images',
				$image_name,
				's3'
			);

			Storage::disk('s3')->setVisibility($path, "public");

			if (!$path | $path === "") {
				return $this->sendError($path, 'User Profile avatar failed to upload!');
			}

			// if the user already has an avatar, then go and delete it.
			if ($user->avatar !== null) {
				Storage::disk('s3')->delete($user->avatar);
			}

			$user->avatar = $path;
			$user->save();
			$success['avatar'] = null;
			if (isset($user->avatar)) {
				$success['avatar'] = $this->getS3Url($path);
			}
			return $this->sendResponse($success, 'User profile avatar uploaded successfully!');
		}
	}

	public function removeAvatar()
	{
		$authUser = Auth::user();
		$user = User::findOrFail($authUser->id);
		Storage::disk('s3')->delete($user->avatar);
		$user->avatar = null;
		$user->save();
		$success['avatar'] = null;
		return $this->sendResponse($success, 'User profile avatar removed successfully.');
	}
}
