<?php

/**
 * Description of MyPost Model
 *
 * @author alto
 */
require_once APPPATH . '/modules/blog/models/Blogpost.php';

class Course extends Blogpost {

	protected $_shares = array();
	protected $_sharesSession = array();

	const TABLE_NAME = 'courses';

	public function getTableName() {
		return self::TABLE_NAME;
	}

	public function validationRulesForInsert($datas) {
		$rules = parent::validationRulesForInsert($datas);
		$rules['description_bbcode'] = $rules['description'];
		$rules['description_bbcode']['field'] = 'description_bbcode';
		unset($rules['description']);
		$rules['content_bbcode'] = $rules['content'];
		$rules['content_bbcode']['field'] = 'content_bbcode';
		$rules['image'] = str_replace('file_required|', "", $rules['image']);
		unset($rules['content']);
		return $rules;
	}

	public function validationRulesForUpdate($datas) {
		$rules = parent::validationRulesForUpdate($datas);
		$rules['description_bbcode'] = $rules['description'];
		$rules['description_bbcode']['field'] = 'description_bbcode';
		unset($rules['description']);
		$rules['content_bbcode'] = $rules['content'];
		$rules['content_bbcode']['field'] = 'content_bbcode';
		unset($rules['content']);
		return $rules;
	}

	public function isPublished($course) {
		return $course->publish != 0;
	}

	public function getPostWithAuthor($where) {
		$this->prepareList();
		return $this->getRowWithAuthor($where);
	}

	public function getPreviousPost($post) {
		$this->prepareList('update_time DESC');
		return $this->getRow(array('update_time >= ' => $post->update_time, $this->getTableName() . '.id !=' => $post->id));
	}

	public function getNextPost($post) {
		$this->prepareList('update_time ASC');
		return $this->getRow(array('update_time <= ' => $post->update_time, $this->getTableName() . '.id !=' => $post->id));
	}

	private function prepareList($order = null) {
		$this->load->model('category');
		$categoryTable = $this->category->getTableName();
		$this->join($categoryTable, $categoryTable . '.id=' . $this->getTableName() . '.category_id');
		if ($order === null) {
			$order = $this->getData('order');
		}
		$this->db->order_by($order);
		$this->db->select($categoryTable . '.name as category_name, ' . $categoryTable . '.alias as category_alias');
	}

	private function prepareListByCat($order = null, $idCat = null) {
		$this->prepareList($order);

		if ($idCat === null) {
			$idCat = $this->getData('catID');
		}
		$this->db->where(array('category_id' => $idCat));
	}

	public function getRowWithAuthor($where, $type = 'object', $columns = '*') {
		$this->load->model('redactor');
		$usersTableName = $this->redactor->getTableName();
		$this->join($usersTableName, $usersTableName . '.id=user_id');
		$this->join('users', 'users.id=user_id');
		$this->db->select($this->db->dbprefix('users') . '.login as author');
		$this->db->select($this->db->dbprefix('users') . '.login as author');
		$this->db->select($this->db->dbprefix('users') . '.login as author');
		$this->db->select($this->db->dbprefix($usersTableName) . '.photo as author_photo');
		$this->db->select($this->db->dbprefix($usersTableName) . '.bio as author_bio');
		return $this->getRow($where, $type, $columns);
	}

	protected function prepareShares() {
		if (!teachsession()) {
			return;
		}
		$this->load->model('sharecourseteachsession');
		$table = $this->getTableName();
		$linkTable = $this->sharecourseteachsession->getTableName();
		$this->join($linkTable, $linkTable . '.course_id=' . $table . '.id and ' . $linkTable . '.teach_session_id=' . teachsession('id'), 'left');
		$this->select('teach_session_id as shared');
	}

	protected function prepareAuthor() {
		$this->load->model('webforceteacher');
		$this->load->model('webforceuser');
		$this->load->model('memberspace/post');
		$tableAuthor = $this->webforceteacher->getTableName();
		$tableWUser = $this->webforceuser->getTableName();
		$tablePost = $this->post->getTableName();
		$table = $this->getTableName();
		$this->join($tableWUser, $tableWUser . '.id = '.$tablePost.'.user_id');
		$this->join($tableAuthor, $tableAuthor . '.id = '.$tableWUser.'.id');
		$this->select($tableAuthor . '.name as author_name');
		$this->select($tableAuthor . '.forname as author_forname');
		$this->select($tableWUser . '.avatar as author_avatar');
	}

	protected function prepareCategories() {
		$this->load->model('category');
		$table = $this->getTableName();
		$catTable = $this->category->getTableName();
		$this->join($catTable, $catTable . '.id=' . $table . '.category_id');
		$this->select($catTable . '.name as category_name,' . $catTable . '.image as category_image, '
				. ''.$catTable.'.color as category_color');
	}

	public function getListWithCategoriesTagsFilesAndShares($limit = null, $offset = null, $type = 'object', $columns = null) {
		$this->prepareFiles();
		$this->prepareAuthor();
		$this->prepareShares();
		$this->prepareCategories();
		$this->prepareTags();
		$results = $this->getList($limit, $offset, $type, $columns);
		return $this->formatFiles($results);
	}

	protected function formatFiles($results) {
		$compileQuery = $this->getCompileQueryMode();
		if ($compileQuery)
			return $results;

		if (!$results)
			return $results;

		foreach ($results as &$course) {
			$course->files = json_decode($course->files);
		}

		return $results;
	}

	protected function formatTags($results) {
		$compileQuery = $this->getCompileQueryMode();
		if ($compileQuery)
			return $results;

		if (!$results)
			return $results;

		foreach ($results as &$course) {
			if ($course->tagsList) {
				$course->tagsList = json_decode($course->tagsList);
			}
		}

		return $results;
	}

	public function prepareFiles() {
		/*
		 * SELECT *,  CONCAT('[',GROUP_CONCAT(files.infos,','),']') as files FROM `courses` LEFT JOIN link_courses_files ON link_courses_files.course_id = courses.id RIGHT JOIN files ON files.id = link_courses_files.file_id
		 */
		$this->db->query('SET SESSION group_concat_max_len = 1000000;');
		$this->select("CONCAT('[',GROUP_CONCAT(DISTINCT CONCAT('{\"file\":\"',files.file,'\",\"name\":\"', files.name,'\",\"infos\":',files.infos,'}') SEPARATOR ','), ']') as files");
		$this->join('link_courses_files', 'link_courses_files.course_id = courses.id', 'left');
		$this->join('files', 'files.id = link_courses_files.file_id', 'left');
		$this->where($this->getTableName() . '.id IS NOT NULL');
		$this->group_by($this->getTableName() . '.id');
	}

	public function prepareTags() {
		$this->db->query('SET SESSION group_concat_max_len = 1000000;');
		$this->load->model('linkcoursetag');
		$linkTable = $this->linkcoursetag->getTableName();
		$this->select("CONCAT('[',GROUP_CONCAT(DISTINCT CONCAT('{\"alias\":\"',tags.alias,'\",\"label\":\"', tags.label,'\"}') SEPARATOR ','), ']') as tagsList");
		$this->join($linkTable, $linkTable . '.course_id = courses.id', 'left');
		$this->join('tags', 'tags.id = ' . $linkTable . '.tag_id', 'left');
		$this->where($this->getTableName() . '.id IS NOT NULL');
		$this->group_by($this->getTableName() . '.id');
	}

	public function getId($id = null, $type = 'object', $columns = NULL) {
		$this->load->model('filebrowser/file');
		$this->load->model('linkcoursefile');
		$this->load->model('linkcoursetag');
		$this->load->model('tag');

		$this->prepareCategories();
		$this->prepareTags();
		$this->prepareAuthor();

		$course = parent::getId($id, $type, $columns);

		if (!$course) {
			return false;
		}

		if (is_object($course)) {
			$course->files = $this->file->getThrough($this->linkcoursefile->getTableName(), 'course', $id);
		} else {
			$course['files'] = $this->file->getThrough($this->linkcoursefile->getTableName(), 'course', $id);
		}

		if (is_object($course)) {
			$course->tagsList = $this->tag->getThrough($this->linkcoursetag->getTableName(), 'course', $id);
		} else {
			$course['tagsList'] = $this->tag->getThrough($this->linkcoursetag->getTableName(), 'course', $id);
		}

		return $course;
	}

	public function getListOrderBy($limit = null, $offset = null, $type = 'object', $columns = null, $order = null) {
		$this->db->order_by($order, 'DESC');
		if ($limit !== null) {
			$this->db->limit($limit, $offset);
		}
		return $this->get(null, $type, $columns);
	}

	public function beforeInsert(&$datas = null) {
		if (!$datas) {
			$datas = $this->toArray();
		}

		$this->load->library('MyBBCodeParser', '', 'parser');
		$this->parser->parse($datas['content_bbcode']);
		$datas['content'] = $this->parser->getAsHtml();

		$this->parser->parse($datas['description_bbcode']);
		$datas['description'] = $this->parser->getAsHtml();

		$datas['alias'] = $this->createAliasFrom($datas['title']);
		parent::beforeInsert($datas);
	}

	public function afterInsert($insert_id, &$to_insert = null) {
		$this->doKeyWordLinking($to_insert, $insert_id);
		$this->doTagsLinking($to_insert, $insert_id);
		$this->doFilesLinking($to_insert, $insert_id);
		$this->doTeacherShares($to_insert, $insert_id);
		$this->load->model('notification');
		$this->notification->create(Notification::TYPE_NEW_COURSE, NULL, array('courseId' => $insert_id));
		if ($to_insert['publish']) {
			$this->notification->create(Notification::TYPE_NEW_PUBLIC_COURSE, NULL, array('courseId' => $insert_id));
		}
		parent::afterInsert($insert_id, $to_insert);
	}

	public function beforeUpdate(&$datas = null, $where = null) {
		if (!$datas) {
			$datas = $this->toArray();
		}

		$this->load->library('MyBBCodeParser', '', 'parser');
		if (isset($datas['content_bbcode'])) {
			$this->parser->parse($datas['content_bbcode']);
			$datas['content'] = $this->parser->getAsHtml();
		}
		if (isset($datas['description_bbcode'])) {
			$this->parser->parse($datas['description_bbcode']);
			$datas['description'] = $this->parser->getAsHtml();
		}
		if(isset($datas['publish']) && $datas['publish']) {
			$this->load->model('notification');
			$where = $where ? $where : $this->buildPrimaryWhere($datas);
			$courses = $this->get($where);
			foreach($courses as $course){
				if( ! $course->publish) {
					$this->notification->create(Notification::TYPE_NEW_PUBLIC_COURSE, NULL, array('courseId' => $course->id));
				}
			}
		}
		if( isset($datas['user_id']) ){
			unset($datas['user_id']);
		}
//		if(isset($datas['title']) && $datas['title']) {
//			$datas['alias'] = $this->createAliasFrom($datas['title'], true);
//		}
		parent::beforeUpdate($datas, $where);
	}

	public function afterUpdate(&$datas = null, $where = null) {
		if (isset($where[$this->db->dbprefix($this->getTableName()) . '.id'])) {
			$id = $where[$this->db->dbprefix($this->getTableName()) . '.id'];
			$this->doFilesLinking($datas, $id);
			$this->doKeyWordLinking($datas, $id);
			$this->doTagsLinking($datas, $id);
			$this->doTeacherShares($datas, $id);
		}
		parent::afterUpdate($datas, $where);
	}

	public function doFilesLinking($datas, $idPost) {
		if (!isset($_POST['files']))
			return;

		$fileIds = $_POST['files'];

		$this->load->model('linkcoursefile');

		$this->linkcoursefile->delete(array('course_id' => $idPost));

		foreach ($fileIds as $fileId) {
			$fileId = trim($fileId);
			if (!$fileId) {
				continue;
			}
			$this->linkcoursefile->link($idPost, $fileId);
		}
	}

	public function doKeyWordLinking($datas, $idPost) {

		if (!isset($datas['keywords']))
			return;

		$keys = $datas['keywords'];

		$keysArray = explode(' ', $keys);

		$this->load->model('linkcoursekeyword');
		$this->load->model('keyword');

		$this->linkcoursekeyword->delete(array('course_id' => $idPost));

		foreach ($keysArray as $key) {
			if (!$key) {
				continue;
			}
			$key = alias($key);
			$idKey = $this->keyword->insert(array('content' => $key));
			$this->linkcoursekeyword->link($idPost, $idKey);
		}
	}

	public function doTagsLinking($datas, $idPost) {

		if (!isset($datas['tags']))
			return;

		$tags = $datas['tags'];
		$tagsArray = explode(',', $tags);

		$this->load->model('linkcoursetag');
		$this->load->model('tag');

		$this->linkcoursetag->delete(array('course_id' => $idPost));
		foreach ($tagsArray as $tag) {
			if($tag = strip_tags(trim($tag))) {
				$idKey = $this->tag->insert(array('label' => $tag));
				$this->linkcoursetag->link($idPost, $idKey);
			}
		}
	}

	public function doTeacherShares($datas, $idPost) {
		if (!user_can('share_to_teacher', 'course', $idPost) || !isset($_POST['teacher_shares']))
			return;

		$teacherShares = $_POST['teacher_shares'];
		$teachersArray = explode(',', $teacherShares);

		$this->load->model('sharecourseteacher');

		$shares = $this->sharecourseteacher->get(array('course_id' => $idPost));

		$alreadySharedTeachers = $shares ? array_map(function($share) {
			return $share->teacher_id;
		}, $shares) : array();

		$this->sharecourseteacher->delete(array('course_id' => $idPost));

		$this->load->model('notification');
		foreach ($teachersArray as $teacher) {
			if (ctype_digit($teacher = trim($teacher))) {
				$this->sharecourseteacher->link($idPost, $teacher);
				if (!in_array($teacher, $alreadySharedTeachers)) {
					$this->notification->create(Notification::TYPE_NEW_COURSE_SHARE_TEACHER, Notification::VISIBILITY_PRIVATE, array('courseId' => $idPost), $teachersArray);
				}
			}
		}

	}

	public function keySearch($limit = null, $offset = null, $search = null, $userId = null) {
		if ($limit !== null) {
			$this->db->limit($offset, $limit);
		}

		if (!$search) {
			$search = $this->getData('search');
		}

		$this->load->model('linkcoursekeyword');
		$this->load->model('keyword');
		$this->load->model('memberspace/user');

		$this->prepareFiles();
		$this->prepareAuthor();
		$this->prepareShares();
		$this->prepareCategories();

		$this->join(Linkcoursekeyword::$TABLE_NAME, $this->db->dbprefix(Linkcoursekeyword::$TABLE_NAME) . '.course_id=' . $this->db->dbprefix(self::TABLE_NAME) . '.id', 'left');
		$this->join(Keyword::TABLE_NAME, $this->db->dbprefix(Keyword::TABLE_NAME) . '.id=' . $this->db->dbprefix(Linkcoursekeyword::$TABLE_NAME) . '.keyword_id', 'left');
//		$this->join(User::$TABLE_NAME, User::$TABLE_NAME . '.id = ' . $this->db->dbprefix(Post::$TABLE_NAME) . '.user_id', 'left');

		$this->db->select('count(*) as matchings');

		$table_hash = Keyword::TABLE_NAME;
		if ( ! is_array($search))
			$search = explode (' ', $search);
			
		$this->db->where_in("$table_hash.content", $search);

//		$this->db->where($this->db->escape($search)." LIKE CONCAT('%',$table_hash.content,'%')");

		$this->db->order_by('matchings DESC');

		$this->db->group_by(self::TABLE_NAME . '.id');

		if ($userId) {
			$this->db->where(array('user_id' => $userId));
		}

		$results = $this->get();
		
		return $this->formatFiles($results);
	}

	// on ne remonte pas avant 1 an (donc $monthLimit + $monthOffset < 12)
	public function getCountsByMonth($monthLimit = 3, $monthOffset = 6) {
		$this->select('count(*) as monthCount, MONTH(FROM_UNIXTIME(creation_time)) as month');
		$this->group_by('month');
		$month = date('n') + 12;
		$monthLimit = ($month - $monthLimit) % 12;
		$this->where(array('MONTH(FROM_UNIXTIME(creation_time)) < ' => $monthLimit, 'MONTH(FROM_UNIXTIME(creation_time)) > ' => $monthLimit - $monthOffset));

		return $this->get();
	}

	public function getByMonthWithAuthorAndCategory($limit, $offset, $month = null) {
		if (!$month) {
			$month = $this->getData('month');
		}
		$ctime = time();
		$this->where(
				array(
					'creation_time > ' => $ctime - (86400 * 366), // on s'assure que les posts de l'archive ont moins d'un an
					'MONTH(FROM_UNIXTIME(creation_time))' => $month,
		));
		return $this->getListWithCategoryAndAuthorAndNbComments($limit, $offset);
	}

	public function getIdWithShares($id = null, $type = 'object', $columns = null) {
		if (!$id) {
			$id = $this->getData('id');
		}
		
		$course = $this->getId($id, $type, $columns);
		
		if (!$course) {
			return $course;
		}

		$this->load->model('sharecourseteacher');
		$this->load->model('webforceteacher');

		$linkTable = $this->sharecourseteacher->getTableName();
		$teacherTable = $this->webforceteacher->getTableName();
		$webforceUserTable = $this->webforceuser->getTableName();

		$this->sharecourseteacher
				->join($teacherTable, $teacherTable . '.id = ' . $linkTable . '.teacher_id','left');
		$this->sharecourseteacher
				->join($webforceUserTable, $webforceUserTable . '.id = ' . $teacherTable . '.id', 'left');

		$this->sharecourseteacher->where(array('course_id' => $id));
		$this->select($teacherTable . '.*,' . $webforceUserTable . '.avatar');

		$shares = $this->sharecourseteacher->getList();

		if (!$shares) {
			$shares = array();
		}
		if (is_object($course)) {
			$course->teacher_shares = json_encode($shares);
		} else {
			$course['teacher_shares'] = json_encode($shares);
		}

		return $course;
	}

	public function getShares($userId, $refresh = false) {
		if (!isset($this->_shares[$userId]) || $refresh) {
			$this->load->model('sharecourseteacher');
			$this->_shares[$userId] = $this->sharecourseteacher->get(array('teacher_id' => $userId));
		}

		return $this->_shares[$userId];
	}

	public function getSharesSession($sessionId, $refresh = false) {
		if (!isset($this->_sharesSession[$sessionId]) || $refresh) {
			$this->load->model('sharecourseteachsession');
			$this->_sharesSession[$sessionId] = $this->sharecourseteachsession->get(array('teach_session_id' => $sessionId));
		}

		return $this->_sharesSession[$sessionId];
	}

	public function isSharedTo($course, $user) {

		if (ctype_digit($course)) {
			$this->load->model('course');
			$course = $this->course->getId($course);
		}

		if ($course->publish) {
			return true;
		}

		$shares = $this->getShares($user->id);

		if (!$shares)
			return false;

		foreach ($shares as $share) {
			if ($share->course_id == $course->id) {
				return true;
			}
		}

		return false;
	}

	public function isSharedToSession($course, $session) {
		$shares = $this->getSharesSession($session->id);

		if (!$shares)
			return false;

		$courseId = ctype_digit($course) ? $course : $course->id;

		foreach ($shares as $share) {
			if ($share->course_id == $courseId) {
				return true;
			}
		}

		return false;
	}

	public function shareWithSession($sessionId, $courseId = null, $date = null) {
		if (!$courseId) {
			$courseId = $this->getData('id');
		}
		if (!$date) {
			$date = $this->getData('date');
		}
		$this->load->model('sharecourseteachsession');

		$this->sharecourseteachsession->link($courseId, $sessionId, $date);
	}

	public function unshareWithSession($sessionId, $courseId = null) {
		if (!$courseId) {
			$courseId = $this->getData('id');
		}
		$this->load->model('sharecourseteachsession');

		$this->sharecourseteachsession->delete(array(
			'course_id' => $courseId,
			'teach_session_id' => $sessionId
		));
	}

	public function getCountGroupedByDayForSession($sessionId, $from, $to) {
		$table = $this->getTableName();
		$this->prepareForSessionOnPeriod($sessionId, $from, $to);
		$linkTable = $this->sharecourseteachsession->getTableName();
		$this->select('COUNT(' . $table . '.id) as count');
		$this->group_by('TO_DAYS(FROM_UNIXTIME(' . $linkTable . '.date))');
		$results = $this->get();
		$counts = array();

		for ($i = $from; $i <= $to + 7200; $i += 86400) {
			$date = date('Y-n-j', $i);

			$counts[$date] = array(
				'timestamp' => $i,
				'count' => 0
			);
		}
		if ($results) {
			foreach ($results as $count) {
				$date = date('Y-n-j', $count->date);
				$counts[$date]['count'] = $count->count;
			}
		}

		return $counts;
	}
	
	public function getGroupByDayForSession($sessionId, $from, $to) {
		$table = $this->getTableName();
		$this->prepareForSessionOnPeriod($sessionId, $from, $to);
		$this->prepareCategories();
		$linkTable = $this->sharecourseteachsession->getTableName();
		$this->order_by($linkTable . '.date DESC');
		$results = $this->get();
		$days = array();

		for ($i = $from; $i <= $to + 7200; $i += 86400) {
			$date = date('Y-n-j', $i);

			$days[$date] = array(
				'timestamp' => $i,
				'courses' => array()
			);
		}
		if ($results) {
			foreach ($results as $course) {
				$date = date('Y-n-j', $course->date);
				$days[$date]['courses'][] = $course;
			}
		}

		return $days;
	}

	public function getCoursesForSessionOnPeriod($sessionId, $from, $to, $withFiles = true) {
		if ($withFiles) {
			$this->prepareFiles();
		}

		$this->prepareForSessionOnPeriod($sessionId, $from, $to);

		if (!$withFiles) {
			return $this->get();
		}

		return $this->formatFiles($this->get());
	}

	private function prepareForSessionOnPeriod($sessionId, $from, $to) {
		$this->load->model('sharecourseteachsession');

		$linkTable = $this->sharecourseteachsession->getTableName();
		$table = $this->getTableName();

		$this->select($linkTable . '.date as date');

		$this->join($linkTable, $linkTable . '.course_id=' . $table . '.id');

		$this->where($linkTable . '.date >= ', $from);
		$this->where($linkTable . '.date <= ', $to);
		$this->where($linkTable . '.teach_session_id', $sessionId);
	}

}
