<?php
declare(strict_types=1);

require_once __DIR__ . "/BaseRepository.php";

class LoanRepository extends BaseRepository
{
  public function getPendingStats(): array
  {
    return [
      "pending_review" => 0,
      "needs_documents" => 0,
      "supervisor_review" => 0,
      "overdue" => 0,
    ];
  }

  public function getApplicationStats(): array
  {
    return [
      "applications_today" => 0,
      "waiting_approval" => 0,
      "auto_approved" => 0,
      "high_risk" => 0,
    ];
  }

  public function getPendingApplications(): array
  {
    return [];
  }

  public function getReleaseStats(): array
  {
    return [
      "ready_for_release" => 0,
      "released_today" => 0,
      "scheduled_releases" => 0,
      "on_hold" => 0,
    ];
  }

  public function getApprovedReleases(): array
  {
    return [];
  }

  public function getReleaseDeletionStats(): array
  {
    return [
      "deletes_pending" => 0,
      "supervisor_approvals" => 0,
      "flagged_issues" => 0,
      "blocked" => 0,
    ];
  }

  public function getReleaseDeletions(): array
  {
    return [];
  }
}
