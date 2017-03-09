import enum
import HTMLParser
import re

from GroupInfo import GroupInfo

MEMBERS_COUNT_REGEX = re.compile('(\d+)\s+members?')
GROUP_ID_REGEX = re.compile('/group/([A-Z0-9]+)')


class TagState(enum.Enum):
    OUTSIDE_GROUP = 0
    INSIDE_GROUP = 1
    INSIDE_GROUP_INFO = 2
    INSIDE_GROUP_INFO_LINK = 3
    INSIDE_GROUP_INFO_DESCRIPTION = 4
    INSIDE_GROUP_COUNT = 5
    INSIDE_GROUP_LASTVIEWED = 6


class GroupResponseParser(HTMLParser.HTMLParser, object):
    def __init__(self):
        super(GroupResponseParser, self).__init__()
        self.state = TagState.OUTSIDE_GROUP
        self.currentGroup = None
        self.groups = []

    def handle_starttag(self, tag, attrs):
        # print "Encountered an start tag <{}>".format(tag)
        class_values = self._get_html_attr('class', attrs)
        if tag == 'li':
            if self.state is not TagState.OUTSIDE_GROUP:
                raise BaseException('Nested li!')
            elif 'groupItem' in class_values:
                self.currentGroup = GroupInfo()
                self.state = TagState.INSIDE_GROUP
        elif tag == 'div':
            if self.state is not TagState.INSIDE_GROUP:
                raise BaseException('Encountered div while not in group!')
            elif 'info' in class_values:
                self.state = TagState.INSIDE_GROUP_INFO
            elif 'memberCount' in class_values:
                self.state = TagState.INSIDE_GROUP_COUNT
            elif 'lastViewed' in class_values:
                self.state = TagState.INSIDE_GROUP_LASTVIEWED
        elif tag == 'a':
            if self.state is not TagState.INSIDE_GROUP_INFO:
                raise BaseException('Encountered a while not in cell info!')
            elif 'link' in class_values:
                self.state = TagState.INSIDE_GROUP_INFO_LINK
                match = GROUP_ID_REGEX.search(self._get_html_attr('href', attrs))
                if match.group(1):
                    self.currentGroup.groupId = match.group(1)
                else:
                    raise BaseException('Couldn\'t find GROUP_ID_REGEX in {}'.format(data))
        elif tag == 'span':
            if self.state is not TagState.INSIDE_GROUP_INFO:
                raise BaseException('Encountered span while not in cell info!')
            elif 'description' in class_values:
                self.state = TagState.INSIDE_GROUP_INFO_DESCRIPTION

    def handle_endtag(self, tag):
        # print "Encountered an end tag </{}>".format(tag)
        if tag == 'li':
            if self.state is not TagState.INSIDE_GROUP:
                raise BaseException('Incorrect state! Was at state {}'.format(self.state))
            self.state = TagState.OUTSIDE_GROUP
            self.groups.append(self.currentGroup)
            self.currentGroup = None
        elif tag == 'div':
            if self.state is TagState.INSIDE_GROUP_INFO or \
                    self.state is TagState.INSIDE_GROUP_COUNT or \
                    self.state is TagState.INSIDE_GROUP_LASTVIEWED or \
                    self.state is TagState.INSIDE_GROUP:
                self.state = TagState.INSIDE_GROUP
                return
            raise BaseException('Incorrect state! Was at state {}'.format(self.state))
        elif tag == 'a':
            if self.state != TagState.INSIDE_GROUP_INFO_LINK:
                raise BaseException('Incorrect state! Was at state {}'.format(self.state))
            self.state = TagState.INSIDE_GROUP_INFO
        elif tag == 'span':
            if self.state != TagState.INSIDE_GROUP_INFO_DESCRIPTION:
                raise BaseException('Incorrect state! Was at state {}'.format(self.state))
            self.state = TagState.INSIDE_GROUP_INFO

    def handle_data(self, data):
        data = data.strip()
        if not data:
            return
        # print "Encountered data: {}".format(data)
        if self.state == TagState.INSIDE_GROUP_INFO_LINK:
            self.currentGroup.groupName = self._sanitize(data)
        elif self.state == TagState.INSIDE_GROUP_INFO_DESCRIPTION:
            self.currentGroup.groupDescription = self._sanitize(data)
        elif self.state == TagState.INSIDE_GROUP_COUNT:
            match = MEMBERS_COUNT_REGEX.search(data)
            if match.group(1):
                self.currentGroup.groupMembers = match.group(1)
            else:
                raise BaseException('Couldn\'t find MEMBERS_COUNT_REGEX in {}'.format(data))

    @staticmethod
    def _get_html_attr(key, attrs):
        for attr in attrs:
            if attr[0] == key:
                return attr[1];
        return ''

    @staticmethod
    def _sanitize(text):
        return text.replace('\r\n', '\n')