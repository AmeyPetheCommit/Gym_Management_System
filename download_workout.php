<?php
session_start();
include 'config.php';
require('./fpdf186/fpdf.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'member') {
    header("Location: login.php");
    exit;
}

$user_email = $_SESSION['user'];

// Fetch membership with workout plan
$sql = "SELECT u.name, p.plan_name, p.level, p.workout_plan
        FROM members m
        JOIN users u ON m.user_id = u.id
        JOIN membership_plans p ON m.plan_id = p.id
        WHERE u.email = ?
        ORDER BY m.id DESC LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("No workout plan found.");
}

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',20);
        $this->SetTextColor(0,0,0);
        $this->Cell(0,10,'Lift',0,0,'C');
        $this->SetTextColor(247,198,0);
        $this->Cell(-150,10,'Kings',0,1,'C');
        $this->Ln(5);
        $this->SetDrawColor(247,198,0);
        $this->SetLineWidth(1);
        $this->Line(10,28,200,28);
        $this->Ln(10);
    }
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(128);
        $this->Cell(0,10,'LiftKings - Workout Guide | Page '.$this->PageNo(),0,0,'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();

// Title
$pdf->SetFont('Arial','B',16);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(0,10,'Workout Plan',0,1,'C');
$pdf->Ln(5);

// Member Info
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,"Member: ".$data['name'],0,1);
$pdf->Cell(0,10,"Plan: ".$data['plan_name']." (".$data['level'].")",0,1);
$pdf->Ln(5);

// Workout Plan Items
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(247,198,0);
$pdf->Cell(0,10,"Your Workout Plan:",0,1);
$pdf->Ln(3);

$pdf->SetFont('Arial','',11);
$pdf->SetTextColor(0,0,0);
$workout_items = explode("\n", $data['workout_plan']);
foreach($workout_items as $item){
    $pdf->MultiCell(0,8,"- ".trim($item));
}

$pdf->Output("D","Workout_Plan.pdf");
?>
