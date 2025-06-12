<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "BNET")]
class Bnet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $uid = 0;

    #[ORM\Column(type: "string", length: 32, nullable: true)]
    private ?string $acctUsername = null;

    #[ORM\Column(type: "string", length: 32, nullable: true)]
    private ?string $username = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $acctUserid = null;

    #[ORM\Column(type: "string", length: 128, nullable: true)]
    private ?string $acctPasshash1 = null;

    #[ORM\Column(type: "string", length: 128, nullable: true)]
    private ?string $acctEmail = null;

    #[ORM\Column(type: "string", length: 16, options: ["default" => "false"])]
    private string $authAdmin = 'false';

    #[ORM\Column(type: "string", length: 16, options: ["default" => "true"])]
    private string $authNormallogin = 'true';

    #[ORM\Column(type: "string", length: 16, options: ["default" => "true"])]
    private string $authChangepass = 'true';

    #[ORM\Column(type: "string", length: 16, options: ["default" => "true"])]
    private string $authChangeprofile = 'true';

    #[ORM\Column(type: "string", length: 16, options: ["default" => "true"])]
    private string $authBotlogin = 'true';

    #[ORM\Column(type: "string", length: 16, options: ["default" => "false"])]
    private string $authOperator = 'false';

    #[ORM\Column(type: "integer", options: ["default" => 0])]
    private int $newAtTeamFlag = 0;

    #[ORM\Column(type: "string", length: 16, options: ["default" => "0"])]
    private string $authLockk = '0';

    #[ORM\Column(type: "string", length: 128, options: ["default" => "1"])]
    private string $authCommandGroups = '1';

    #[ORM\Column(type: "integer", options: ["default" => 0])]
    private int $acctLastloginTime = 0;

    #[ORM\Column(type: "string", length: 128, nullable: true)]
    private ?string $acctLastloginOwner = null;

    #[ORM\Column(type: "string", length: 128, nullable: true)]
    private ?string $acctLastloginClienttag = null;

    #[ORM\Column(type: "string", length: 32, nullable: true)]
    private ?string $acctLastloginIp = null;

    #[ORM\Column(type: "string", length: 128, nullable: true)]
    private ?string $acctCtime = null;

    #[ORM\Column(type: "string", length: 128, nullable: true)]
    private ?string $authVoiceDiabloII1 = null;

    #[ORM\Column(type: "string", length: 128, nullable: true)]
    private ?string $authOperatorDiabloII1 = null;

    #[ORM\Column(type: "string", length: 128, nullable: true)]
    private ?string $authAdminDiabloII1 = null;

    #[ORM\Column(type: "string", length: 128, nullable: true)]
    private ?string $authAdminBlades = null;

    #[ORM\Column(type: "string", length: 128, nullable: true)]
    private ?string $authOperatorBlades = null;

    #[ORM\Column(type: "string", length: 128, nullable: true)]
    private ?string $authVoiceBlades = null;

    #[ORM\Column(type: "string", length: 128, nullable: true)]
    private ?string $authVoice1vs1 = null;

    #[ORM\Column(type: "string", length: 128, nullable: true)]
    private ?string $authAdminVeso = null;

    #[ORM\Column(type: "string", length: 128, nullable: true)]
    private ?string $authOperatorVeso = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $cPassword = null;

    #[ORM\Column(type: "string", length: 16, options: ["default" => "0"])]
    private string $authMute = '0';

    #[ORM\Column(type: "string", length: 128, nullable: true)]
    private ?string $authOperatorAuratum = null;

    #[ORM\Column(type: "string", length: 6, options: ["default" => "false"])]
    private string $authLock = 'false';

    #[ORM\Column(type: "integer", options: ["default" => 0])]
    private int $authLocktime = 0;

    #[ORM\Column(type: "string", length: 128, nullable: true)]
    private ?string $authLockreason = null;

    #[ORM\Column(type: "integer", options: ["default" => 0])]
    private int $authMutetime = 0;

    #[ORM\Column(type: "string", length: 128, nullable: true)]
    private ?string $authMutereason = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'bnetAccounts')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private ?User $user = null;

    // Getters and setters for all properties
    public function getUid(): int 
    { 
        return $this->uid; 
    }

    public function setUid(int $uid): static
    {
        $this->uid = $uid;
        $this->acctUserid = $uid; // Keep acctUserid in sync with uid
        return $this;
    }

    public function getAcctUsername(): ?string { 
        return $this->acctUsername; 
    }

    public function setAcctUsername(?string $acctUsername): static
    {
        $this->acctUsername = $acctUsername;
        $this->username = $acctUsername !== null ? mb_strtolower($acctUsername) : null;
        return $this;
    }

    public function getUsername(): ?string 
    { 
        return $this->username; 
    }

    public function setUsername(?string $username): static
    {
        // username is always set via setAcctUsername, so this is a no-op or could throw if you want strictness
        return $this;
    }

    public function getAcctUserid(): ?int 
    { 
        return $this->acctUserid; 
    }

    public function setAcctUserid(?int $acctUserid): static
    {
        // acctUserid is always set via setUid, so this is a no-op or could throw if you want strictness
        return $this;
    }

    public function getAcctPasshash1(): ?string 
    { 
        return $this->acctPasshash1; 
    }

    public function setAcctPasshash1(?string $acctPasshash1): static 
    { 
        $this->acctPasshash1 = $acctPasshash1; 
        return $this; 
    }

    public function getAcctEmail(): ?string 
    { 
        return $this->acctEmail; 
    }
    
    public function setAcctEmail(?string $acctEmail): static 
    { 
        $this->acctEmail = $acctEmail; 
        return $this; 
    }

    public function getAuthAdmin(): string 
    { 
        return $this->authAdmin; 
    }

    public function setAuthAdmin(string $authAdmin): static 
    { 
        $this->authAdmin = $authAdmin; 
        return $this; 
    }

    public function getAuthNormallogin(): string 
    { 
        return $this->authNormallogin; 
    }

    public function setAuthNormallogin(string $authNormallogin): static 
    { 
        $this->authNormallogin = $authNormallogin; 
        return $this; 
    }

    public function getAuthChangepass(): string 
    { 
        return $this->authChangepass; 
    }

    public function setAuthChangepass(string $authChangepass): static 
    { 
        $this->authChangepass = $authChangepass;
        return $this; 
    }

    public function getAuthChangeprofile(): string 
    { 
        return $this->authChangeprofile; 
    }

    public function setAuthChangeprofile(string $authChangeprofile): static 
    { 
        $this->authChangeprofile = $authChangeprofile; 
        return $this; 
    }

    public function getAuthBotlogin(): string 
    { 
        return $this->authBotlogin; 
    }

    public function setAuthBotlogin(string $authBotlogin): static 
    { 
        $this->authBotlogin = $authBotlogin; 
        return $this; }

    public function getAuthOperator(): string 
    { 
        return $this->authOperator; 
    }

    public function setAuthOperator(string $authOperator): static 
    { 
        $this->authOperator = $authOperator; 
        return $this; 
    }

    public function getNewAtTeamFlag(): int 
    { 
        return $this->newAtTeamFlag; 
    }

    public function setNewAtTeamFlag(int $newAtTeamFlag): static 
    { 
        $this->newAtTeamFlag = $newAtTeamFlag; 
        return $this; 
    }

    public function getAuthLockk(): string 
    { 
        return $this->authLockk; 
    }

    public function setAuthLockk(string $authLockk): static 
    { 
        $this->authLockk = $authLockk; 
        return $this; 
    }

    public function getAuthCommandGroups(): string 
    { 
        return $this->authCommandGroups; 
    }

    public function setAuthCommandGroups(string $authCommandGroups): static 
    { 
        $this->authCommandGroups = $authCommandGroups; 
        return $this; 
    }

    public function getAcctLastloginTime(): int 
    { 
        return $this->acctLastloginTime; 
    }

    public function setAcctLastloginTime(int $acctLastloginTime): static 
    { 
        $this->acctLastloginTime = $acctLastloginTime; 
        return $this; 
    }

    public function getAcctLastloginOwner(): ?string 
    { 
        return $this->acctLastloginOwner; 
    }

    public function setAcctLastloginOwner(?string $acctLastloginOwner): static 
    { 
        $this->acctLastloginOwner = $acctLastloginOwner; 
        return $this; 
    }

    public function getAcctLastloginClienttag(): ?string 
    {
        return $this->acctLastloginClienttag; 
    }

    public function setAcctLastloginClienttag(?string $acctLastloginClienttag): static 
    { 
        $this->acctLastloginClienttag = $acctLastloginClienttag; 
        return $this; 
    }

    public function getAcctLastloginIp(): ?string 
    { 
        return $this->acctLastloginIp; 
    }

    public function setAcctLastloginIp(?string $acctLastloginIp): static 
    { 
        $this->acctLastloginIp = $acctLastloginIp; 
        return $this; 
    }

    public function getAcctCtime(): ?string 
    { 
        return $this->acctCtime; 
    }

    public function setAcctCtime(?string $acctCtime = null): static 
    {
        if ($acctCtime === null) {
            $this->acctCtime = (new \DateTimeImmutable())->format('Y-m-d H:i:s');
        } else {
            $this->acctCtime = $acctCtime;
        }
        return $this;
    }

    public function getAuthVoiceDiabloII1(): ?string 
    { 
        return $this->authVoiceDiabloII1; 
    }

    public function setAuthVoiceDiabloII1(?string $authVoiceDiabloII1): static 
    { 
        $this->authVoiceDiabloII1 = $authVoiceDiabloII1; 
        return $this; 
    }

    public function getAuthOperatorDiabloII1(): ?string 
    { 
        return $this->authOperatorDiabloII1; 
    }

    public function setAuthOperatorDiabloII1(?string $authOperatorDiabloII1): static 
    { 
        $this->authOperatorDiabloII1 = $authOperatorDiabloII1; 
        return $this; 
    }

    public function getAuthAdminDiabloII1(): ?string 
    { 
        return $this->authAdminDiabloII1; 
    }

    public function setAuthAdminDiabloII1(?string $authAdminDiabloII1): static 
    { 
        $this->authAdminDiabloII1 = $authAdminDiabloII1; 
        return $this; 
    }

    public function getAuthAdminBlades(): ?string 
    { 
        return $this->authAdminBlades; 
    }

    public function setAuthAdminBlades(?string $authAdminBlades): static 
    { 
        $this->authAdminBlades = $authAdminBlades; 
        return $this; 
    }

    public function getAuthOperatorBlades(): ?string 
    { 
        return $this->authOperatorBlades; 
    }

    public function setAuthOperatorBlades(?string $authOperatorBlades): static 
    { 
        $this->authOperatorBlades = $authOperatorBlades; 
        return $this; 
    }

    public function getAuthVoiceBlades(): ?string 
    { 
        return $this->authVoiceBlades; 
    }

    public function setAuthVoiceBlades(?string $authVoiceBlades): static 
    { 
        $this->authVoiceBlades = $authVoiceBlades; 
        return $this; 
    }

    public function getAuthVoice1vs1(): ?string 
    { 
        return $this->authVoice1vs1; 
    }

    public function setAuthVoice1vs1(?string $authVoice1vs1): static 
    { 
        $this->authVoice1vs1 = $authVoice1vs1; 
        return $this; 
    }

    public function getAuthAdminVeso(): ?string 
    { 
        return $this->authAdminVeso; 
    }

    public function setAuthAdminVeso(?string $authAdminVeso): static 
    { 
        $this->authAdminVeso = $authAdminVeso; 
        return $this; 
    }

    public function getAuthOperatorVeso(): ?string 
    { 
        return $this->authOperatorVeso; 
    }

    public function setAuthOperatorVeso(?string $authOperatorVeso): static 
    { 
        $this->authOperatorVeso = $authOperatorVeso; 
        return $this; 
    }

    public function getCPassword(): ?string 
    { 
        return $this->cPassword; 
    }

    public function setCPassword(?string $cPassword): static 
    { 
        $this->cPassword = $cPassword; 
        return $this; 
    }

    public function getAuthMute(): string 
    { 
        return $this->authMute; 
    }

    public function setAuthMute(string $authMute): static 
    { 
        $this->authMute = $authMute; 
        return $this; 
    }

    public function getAuthOperatorAuratum(): ?string 
    { 
        return $this->authOperatorAuratum; 
    }

    public function setAuthOperatorAuratum(?string $authOperatorAuratum): static 
    { 
        $this->authOperatorAuratum = $authOperatorAuratum; 
        return $this; 
    }

    public function getAuthLock(): string 
    { 
        return $this->authLock; 
    }

    public function setAuthLock(string $authLock): static 
    { 
        $this->authLock = $authLock; 
        return $this; 
    }

    public function getAuthLocktime(): int 
    { 
        return $this->authLocktime; 
    }

    public function setAuthLocktime(int $authLocktime): static 
    { 
        $this->authLocktime = $authLocktime; 
        return $this; 
    }

    public function getAuthLockreason(): ?string 
    { 
        return $this->authLockreason; 
    }

    public function setAuthLockreason(?string $authLockreason): static 
    { 
        $this->authLockreason = $authLockreason; 
        return $this; 
    }

    public function getAuthMutetime(): int 
    { 
        return $this->authMutetime; 
    }

    public function setAuthMutetime(int $authMutetime): static 
    { 
        $this->authMutetime = $authMutetime; 
        return $this; 
    }

    public function getAuthMutereason(): ?string 
    { 
        return $this->authMutereason; 
    }

    public function setAuthMutereason(?string $authMutereason): static 
    { 
        $this->authMutereason = $authMutereason; 
        return $this; 
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }
}
