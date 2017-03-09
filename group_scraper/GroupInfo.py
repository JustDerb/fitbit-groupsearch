class GroupInfo:
    def __init__(self):
        self.groupId = ''
        self.groupDescription = ''
        self.groupMembers = 0
        self.groupName = ''

    def __str__(self):
        return 'GroupInfo[id="{}",name="{}",members="{}",description="{}"]'.format(
            self.groupId,
            self.groupName,
            self.groupMembers,
            self.groupDescription
        )