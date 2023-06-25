<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Activity
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property int $project_id
 * @property int $task_id
 * @property bool $private
 * @property \App\Enums\ActivityType $type
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $activity
 * @property-read \App\Models\User|null $author
 * @property-read \App\Models\Comment|null $comment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read mixed $description
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Project|null $project
 * @property-read \App\Models\Task|null $task
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Activity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Activity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Activity query()
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity wherePrivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperActivity {}
}

namespace App\Models{
/**
 * App\Models\Comment
 *
 * @property int $activity_id
 * @property string|null $content
 * @property-read \App\Models\User|null $author
 * @property-read \App\Models\Task|null $task
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereContent($value)
 * @mixin \Eloquent
 */
	class IdeHelperComment {}
}

namespace App\Models{
/**
 * App\Models\Group
 *
 * @property int $id
 * @property string $name
 * @property string|null $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $color
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group newModelQuery()
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group newQuery()
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group query()
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group search(?string $phrase = null)
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group sortByString(?string $string = null)
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group whereColor($value)
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group whereCreatedAt($value)
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group whereId($value)
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group whereName($value)
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group whereType($value)
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperGroup {}
}

namespace App\Models{
/**
 * App\Models\Project
 *
 * @property int $id
 * @property int|null $client_id
 * @property string $name
 * @property string|null $description
 * @property mixed|null $custom_fields
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User|null $client
 * @property-read mixed $avatar
 * @property-read \App\Models\Activity|null $latestActivity
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $members
 * @property-read int|null $members_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 * @property-read int|null $tasks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Time> $times
 * @property-read int|null $times_count
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCustomFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperProject {}
}

namespace App\Models{
/**
 * App\Models\Task
 *
 * @property int $id
 * @property int $project_id
 * @property int $author_id
 * @property int $number
 * @property string $subject
 * @property string|null $description
 * @property int $private
 * @property mixed|null $custom_fields
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $assignees
 * @property-read int|null $assignees_count
 * @property-read \App\Models\User|null $author
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read mixed $url
 * @property-read \App\Models\Activity|null $latestActivity
 * @property-read \App\Models\Project|null $project
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Time> $times
 * @property-read int|null $times_count
 * @method static \Illuminate\Database\Eloquent\Builder|Task acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Task filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Task ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task query()
 * @method static \Illuminate\Database\Eloquent\Builder|Task setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Task setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Task setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Task usingSearchString($string)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCustomFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task wherePrivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperTask {}
}

namespace App\Models{
/**
 * App\Models\Time
 *
 * @property int $id
 * @property int $project_id
 * @property int|null $task_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $start_at
 * @property \Illuminate\Support\Carbon|null $end_at
 * @property int|null $time
 * @property string|null $comment
 * @property int $author_id
 * @property-read \App\Models\User|null $author
 * @property-read \App\Models\Task|null $task
 * @method static \Illuminate\Database\Eloquent\Builder|Time newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Time newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Time query()
 * @method static \Illuminate\Database\Eloquent\Builder|Time whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Time whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Time whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Time whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Time whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Time whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Time whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Time whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Time whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Time whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperTime {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $public_email
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $job_title
 * @property string|null $phone
 * @property-read mixed $active_time
 * @property-read mixed $avatar
 * @property-read mixed $full_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Group> $groups
 * @property-read int|null $groups_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Time> $times
 * @property-read int|null $times_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \App\QueryBuilders\UserQueryBuilder|User newModelQuery()
 * @method static \App\QueryBuilders\UserQueryBuilder|User newQuery()
 * @method static \App\QueryBuilders\UserQueryBuilder|User query()
 * @method static \App\QueryBuilders\UserQueryBuilder|User search(?string $phrase = null)
 * @method static \App\QueryBuilders\UserQueryBuilder|User sortByString(?string $string = null)
 * @method static \App\QueryBuilders\UserQueryBuilder|User whereCreatedAt($value)
 * @method static \App\QueryBuilders\UserQueryBuilder|User whereEmail($value)
 * @method static \App\QueryBuilders\UserQueryBuilder|User whereEmailVerifiedAt($value)
 * @method static \App\QueryBuilders\UserQueryBuilder|User whereFirstName($value)
 * @method static \App\QueryBuilders\UserQueryBuilder|User whereId($value)
 * @method static \App\QueryBuilders\UserQueryBuilder|User whereJobTitle($value)
 * @method static \App\QueryBuilders\UserQueryBuilder|User whereLastName($value)
 * @method static \App\QueryBuilders\UserQueryBuilder|User whereName($value)
 * @method static \App\QueryBuilders\UserQueryBuilder|User wherePassword($value)
 * @method static \App\QueryBuilders\UserQueryBuilder|User wherePhone($value)
 * @method static \App\QueryBuilders\UserQueryBuilder|User wherePublicEmail($value)
 * @method static \App\QueryBuilders\UserQueryBuilder|User whereRememberToken($value)
 * @method static \App\QueryBuilders\UserQueryBuilder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperUser {}
}

