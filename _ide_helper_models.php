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
 * @property int|null $comment_id
 * @property \App\Enums\ActivityType $type
 * @property array|null $details
 * @property bool $private
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $activity
 * @property-read \App\Models\User|null $author
 * @property-read \App\Models\Comment|null $comment
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Project|null $project
 * @property-read \App\Models\Task|null $task
 * @property-read \App\Models\User|null $user
 * @method static \App\QueryBuilders\ActivityQueryBuilder|Activity disableCache()
 * @method static \App\QueryBuilders\ActivityQueryBuilder|Activity newModelQuery()
 * @method static \App\QueryBuilders\ActivityQueryBuilder|Activity newQuery()
 * @method static \App\QueryBuilders\ActivityQueryBuilder|Activity query()
 * @method static \App\QueryBuilders\ActivityQueryBuilder|Activity search(?string $phrase = null)
 * @method static \App\QueryBuilders\ActivityQueryBuilder|Activity sortByString(?string $string = null)
 * @method static \App\QueryBuilders\ActivityQueryBuilder|Activity whereCommentId($value)
 * @method static \App\QueryBuilders\ActivityQueryBuilder|Activity whereCreatedAt($value)
 * @method static \App\QueryBuilders\ActivityQueryBuilder|Activity whereDetails($value)
 * @method static \App\QueryBuilders\ActivityQueryBuilder|Activity whereId($value)
 * @method static \App\QueryBuilders\ActivityQueryBuilder|Activity wherePrivate($value)
 * @method static \App\QueryBuilders\ActivityQueryBuilder|Activity whereProjectId($value)
 * @method static \App\QueryBuilders\ActivityQueryBuilder|Activity whereTaskId($value)
 * @method static \App\QueryBuilders\ActivityQueryBuilder|Activity whereType($value)
 * @method static \App\QueryBuilders\ActivityQueryBuilder|Activity whereUpdatedAt($value)
 * @method static \App\QueryBuilders\ActivityQueryBuilder|Activity whereUserId($value)
 * @method static \App\QueryBuilders\ActivityQueryBuilder|Activity withCacheCooldownSeconds(?int $seconds = null)
 * @mixin \Eloquent
 */
	class IdeHelperActivity {}
}

namespace App\Models{
/**
 * App\Models\Comment
 *
 * @property int $id
 * @property string|null $content
 * @property-read \App\Models\User|null $author
 * @property-read \App\Models\Task|null $task
 * @method static \App\QueryBuilders\CommentQueryBuilder|Comment disableCache()
 * @method static \App\QueryBuilders\CommentQueryBuilder|Comment newModelQuery()
 * @method static \App\QueryBuilders\CommentQueryBuilder|Comment newQuery()
 * @method static \App\QueryBuilders\CommentQueryBuilder|Comment query()
 * @method static \App\QueryBuilders\CommentQueryBuilder|Comment search(?string $phrase = null)
 * @method static \App\QueryBuilders\CommentQueryBuilder|Comment sortByString(?string $string = null)
 * @method static \App\QueryBuilders\CommentQueryBuilder|Comment whereContent($value)
 * @method static \App\QueryBuilders\CommentQueryBuilder|Comment whereId($value)
 * @method static \App\QueryBuilders\CommentQueryBuilder|Comment withCacheCooldownSeconds(?int $seconds = null)
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
 * @property string|null $description
 * @property string|null $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $color
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group disableCache()
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group newModelQuery()
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group newQuery()
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group query()
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group search(?string $phrase = null)
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group sortByString(?string $string = null)
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group whereColor($value)
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group whereCreatedAt($value)
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group whereDescription($value)
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group whereId($value)
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group whereName($value)
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group whereType($value)
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group whereUpdatedAt($value)
 * @method static \App\QueryBuilders\GroupQueryBuilder|Group withCacheCooldownSeconds(?int $seconds = null)
 * @mixin \Eloquent
 */
	class IdeHelperGroup {}
}

namespace App\Models{
/**
 * App\Models\Priority
 *
 * @property int $id
 * @property string $name
 * @property string $color
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 * @property-read int|null $tasks_count
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Priority disableCache()
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Priority newModelQuery()
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Priority newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Priority onlyTrashed()
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Priority query()
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Priority search(?string $phrase = null)
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Priority sortByString(?string $string = null)
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Priority whereColor($value)
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Priority whereDeletedAt($value)
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Priority whereId($value)
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Priority whereName($value)
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Priority withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Priority withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Priority withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperPriority {}
}

namespace App\Models{
/**
 * App\Models\Project
 *
 * @property int $id
 * @property int|null $client_id
 * @property int|null $latest_activity_id
 * @property string|null $latest_activity_at
 * @property string $name
 * @property string|null $description
 * @property mixed|null $custom_fields
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User|null $client
 * @property-read \App\Models\Activity|null $latestActivity
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $members
 * @property-read int|null $members_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 * @property-read int|null $tasks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Time> $times
 * @property-read int|null $times_count
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Project disableCache()
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Project newModelQuery()
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project onlyTrashed()
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Project query()
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Project search(?string $phrase = null)
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Project sortByString(?string $string = null)
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Project whereClientId($value)
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Project whereCreatedAt($value)
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Project whereCustomFields($value)
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Project whereDeletedAt($value)
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Project whereDescription($value)
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Project whereId($value)
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Project whereLatestActivityAt($value)
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Project whereLatestActivityId($value)
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Project whereName($value)
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Project whereUpdatedAt($value)
 * @method static \App\QueryBuilders\ProjectQueryBuilder|Project withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Project withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Project withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperProject {}
}

namespace App\Models{
/**
 * App\Models\Status
 *
 * @property int $id
 * @property string $name
 * @property string $color
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 * @property-read int|null $tasks_count
 * @method static \App\QueryBuilders\StatusQueryBuilder|Status disableCache()
 * @method static \App\QueryBuilders\StatusQueryBuilder|Status newModelQuery()
 * @method static \App\QueryBuilders\StatusQueryBuilder|Status newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Status onlyTrashed()
 * @method static \App\QueryBuilders\StatusQueryBuilder|Status query()
 * @method static \App\QueryBuilders\StatusQueryBuilder|Status search(?string $phrase = null)
 * @method static \App\QueryBuilders\StatusQueryBuilder|Status sortByString(?string $string = null)
 * @method static \App\QueryBuilders\StatusQueryBuilder|Status whereColor($value)
 * @method static \App\QueryBuilders\StatusQueryBuilder|Status whereDeletedAt($value)
 * @method static \App\QueryBuilders\StatusQueryBuilder|Status whereId($value)
 * @method static \App\QueryBuilders\StatusQueryBuilder|Status whereName($value)
 * @method static \App\QueryBuilders\StatusQueryBuilder|Status withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Status withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Status withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperStatus {}
}

namespace App\Models{
/**
 * App\Models\Task
 *
 * @property int $id
 * @property int $project_id
 * @property int $author_id
 * @property int|null $status_id
 * @property int|null $priority_id
 * @property int|null $latest_activity_id
 * @property string|null $latest_activity_at
 * @property int $number
 * @property string $subject
 * @property string|null $description
 * @property bool $private
 * @property array|null $tags
 * @property string|null $start_date
 * @property string|null $end_date
 * @property int $billable
 * @property int|null $estimation
 * @property mixed|null $custom_fields
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $assignees
 * @property-read int|null $assignees_count
 * @property-read \App\Models\User|null $author
 * @property-read \App\Models\Activity|null $latestActivity
 * @property-read \App\Models\Priority|null $priority
 * @property-read \App\Models\Project|null $project
 * @property-read \App\Models\Status|null $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Time> $times
 * @property-read int|null $times_count
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task newModelQuery()
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task onlyTrashed()
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task query()
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task search(?string $phrase = null)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task sortByString(?string $string = null)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task whereAuthorId($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task whereBillableStatus($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task whereCreatedAt($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task whereCustomFields($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task whereDeletedAt($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task whereDescription($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task whereEndDate($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task whereEstimation($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task whereId($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task whereLatestActivityAt($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task whereLatestActivityId($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task whereNumber($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task wherePriorityId($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task wherePrivate($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task whereProjectId($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task whereStartDate($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task whereStatusId($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task whereSubject($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task whereTags($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Task whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Task withoutTrashed()
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
 * @method static \App\QueryBuilders\TaskQueryBuilder|Time disableCache()
 * @method static \App\QueryBuilders\TaskQueryBuilder|Time newModelQuery()
 * @method static \App\QueryBuilders\TaskQueryBuilder|Time newQuery()
 * @method static \App\QueryBuilders\TaskQueryBuilder|Time query()
 * @method static \App\QueryBuilders\TaskQueryBuilder|Time search(?string $phrase = null)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Time sortByString(?string $string = null)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Time whereAuthorId($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Time whereComment($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Time whereCreatedAt($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Time whereEndAt($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Time whereId($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Time whereProjectId($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Time whereStartAt($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Time whereTaskId($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Time whereTime($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Time whereUpdatedAt($value)
 * @method static \App\QueryBuilders\TaskQueryBuilder|Time withCacheCooldownSeconds(?int $seconds = null)
 * @mixin \Eloquent
 */
	class IdeHelperTime {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property int|null $active_time_id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $public_email
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $job_title
 * @property string|null $phone
 * @property-read \App\Models\Time|null $activeTime
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Group> $groups
 * @property-read int|null $groups_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Time> $times
 * @property-read int|null $times_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \App\QueryBuilders\UserQueryBuilder|User newModelQuery()
 * @method static \App\QueryBuilders\UserQueryBuilder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \App\QueryBuilders\UserQueryBuilder|User query()
 * @method static \App\QueryBuilders\UserQueryBuilder|User search(?string $phrase = null)
 * @method static \App\QueryBuilders\UserQueryBuilder|User sortByString(?string $string = null)
 * @method static \App\QueryBuilders\UserQueryBuilder|User whereActiveTimeId($value)
 * @method static \App\QueryBuilders\UserQueryBuilder|User whereCreatedAt($value)
 * @method static \App\QueryBuilders\UserQueryBuilder|User whereDeletedAt($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperUser {}
}

