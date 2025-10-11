<?php

class QnaModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllActiveQna() {
        $stmt = $this->db->prepare("SELECT * FROM qna WHERE IsActive = 1 ORDER BY DisplayOrder ASC, QnaID ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllQna($search = '', $limit = 10, $offset = 0) {
        $query = "SELECT q.*, CONCAT(a.Fname, ' ', a.Lname) AS CreatedByName 
                  FROM qna q 
                  LEFT JOIN admin a ON q.CreatedBy = a.AdminID";
        $params = [];

        if (!empty($search)) {
            $query .= " WHERE (q.Question LIKE :search_question OR q.Answer LIKE :search_answer)";
            $params[':search_question'] = "%$search%";
            $params[':search_answer'] = "%$search%";
        }

        $query .= " ORDER BY q.DisplayOrder ASC, q.QnaID ASC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($query);

        if (!empty($search)) {
            $stmt->bindValue(':search_question', "%$search%", PDO::PARAM_STR);
            $stmt->bindValue(':search_answer', "%$search%", PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getQnaCount($search = '') {
        $query = "SELECT COUNT(*) FROM qna";
        $params = [];

        if (!empty($search)) {
            $query .= " WHERE Question LIKE :search_question OR Answer LIKE :search_answer";
            $params = [':search_question' => "%$search%", ':search_answer' => "%$search%"];
        }

        $stmt = $this->db->prepare($query);
        if (!empty($search)) {
            $stmt->bindValue(':search_question', "%$search%", PDO::PARAM_STR);
            $stmt->bindValue(':search_answer', "%$search%", PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getQnaById($id) {
        $stmt = $this->db->prepare("SELECT * FROM qna WHERE QnaID = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addQna($question, $answer, $displayOrder, $isActive, $adminId) {
        $query = "INSERT INTO qna (Question, Answer, DisplayOrder, IsActive, CreatedBy) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$question, $answer, $displayOrder, $isActive, $adminId]);
    }

    public function updateQna($id, $question, $answer, $displayOrder, $isActive) {
        $query = "UPDATE qna SET Question = ?, Answer = ?, DisplayOrder = ?, IsActive = ? 
                  WHERE QnaID = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$question, $answer, $displayOrder, $isActive, $id]);
    }

    public function deleteQna($id) {
        $stmt = $this->db->prepare("DELETE FROM qna WHERE QnaID = ?");
        return $stmt->execute([$id]);
    }
}
