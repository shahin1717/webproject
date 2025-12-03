<?php
class DrivingExperience
{
    private int $expID;
    private string $date;
    private string $startTime;
    private string $endTime;
    private float $kilometers;
    private int $weatherID;
    private int $surfaceID;
    private int $trafficID;
    private int $accidentID;

    public function __construct(
        int $expID,
        string $date,
        string $startTime,
        string $endTime,
        float $kilometers,
        int $weatherID,
        int $surfaceID,
        int $trafficID,
        int $accidentID
    ) {
        $this->expID       = $expID;
        $this->date        = $date;
        $this->startTime   = $startTime;
        $this->endTime     = $endTime;
        $this->kilometers  = $kilometers;
        $this->weatherID   = $weatherID;
        $this->surfaceID   = $surfaceID;
        $this->trafficID   = $trafficID;
        $this->accidentID  = $accidentID;
    }

    // ----------- GETTERS ----------- //

    public function getID(): int {
        return $this->expID;
    }

    public function getDate(): string {
        return $this->date;
    }

    public function getStartTime(): string {
        return $this->startTime;
    }

    public function getEndTime(): string {
        return $this->endTime;
    }

    public function getKilometers(): float {
        return $this->kilometers;
    }

    public function getWeatherID(): int {
        return $this->weatherID;
    }

    public function getSurfaceID(): int {
        return $this->surfaceID;
    }

    public function getTrafficID(): int {
        return $this->trafficID;
    }

    public function getAccidentID(): int {
        return $this->accidentID;
    }

    // ----------- SETTERS ----------- //

    public function setDate(string $date): void {
        $this->date = $date;
    }

    public function setStartTime(string $startTime): void {
        $this->startTime = $startTime;
    }

    public function setEndTime(string $endTime): void {
        $this->endTime = $endTime;
    }

    public function setKilometers(float $km): void {
        $this->kilometers = $km;
    }

    public function setWeatherID(int $id): void {
        $this->weatherID = $id;
    }

    public function setSurfaceID(int $id): void {
        $this->surfaceID = $id;
    }

    public function setTrafficID(int $id): void {
        $this->trafficID = $id;
    }

    public function setAccidentID(int $id): void {
        $this->accidentID = $id;
    }
}
?>
