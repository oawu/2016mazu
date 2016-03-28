//
//  MessageTableViewCell.m
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/28.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "MessageTableViewCell.h"

@implementation MessageTableViewCell

- (MessageTableViewCell *) initCellWithStyle:(Message *)message style:(UITableViewCellStyle)style reuseIdentifier:(nullable NSString *)reuseIdentifier {
    self = [super initWithStyle:style reuseIdentifier:reuseIdentifier];
    if (self) {
        [self initUI:message];
    }
    return self;
}
- (void)initUI:(Message *)message {
    self.message = message;
    
    [self setSelectionStyle:UITableViewCellSelectionStyleNone];
    [self.contentView.layer setBackgroundColor:[UIColor colorWithRed:0.95 green:0.95 blue:0.95 alpha:1].CGColor];

    
    [self initBorder];
    [self initAvatar];
    [self initMessage];
    [self initIp];
    [self initTime];
//    [self initTop:polyline];
//    [self initBottom:polyline];
//    [self initAvatar:polyline];
}
-(void)initAvatar {
    
    self.avatar = [UIImageView new];
    [self.avatar setTranslatesAutoresizingMaskIntoConstraints:NO];

    [self.avatar.layer setBorderColor:self.message.isAdmin ? [UIColor colorWithRed:0.67 green:0.19 blue:0.18 alpha:1.00].CGColor : [UIColor colorWithRed:0 green:0 blue:0 alpha:.5].CGColor];
    [self.avatar.layer setBorderWidth:1.0f / [UIScreen mainScreen].scale];
    
    [self.avatar.layer setCornerRadius:20];
    [self.avatar setClipsToBounds:YES];
    
    [self.avatar setImage:[[UIImage imageNamed:self.message.isAdmin ? @"user_admin" : @"user"] imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal]];

    [self.avatar setContentMode:UIViewContentModeScaleAspectFill];
    [self.avatar setClipsToBounds:YES];
    
    [self.border addSubview:self.avatar];
    [self.border addConstraint:[NSLayoutConstraint constraintWithItem:self.avatar attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.border attribute:NSLayoutAttributeTop multiplier:1 constant:15]];
    [self.border addConstraint:[NSLayoutConstraint constraintWithItem:self.avatar attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.border attribute:NSLayoutAttributeLeft multiplier:1 constant:10]];
    [self.border addConstraint:[NSLayoutConstraint constraintWithItem:self.avatar attribute:NSLayoutAttributeWidth relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:40]];
    [self.border addConstraint:[NSLayoutConstraint constraintWithItem:self.avatar attribute:NSLayoutAttributeHeight relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:40]];

}
-(void)initTime {
    self.time = [UILabel new];
    [self.time setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.time setText:self.message.time];
    [self.time setTextColor:[UIColor colorWithRed:0.09 green:0.09 blue:0.09 alpha:.70]];

    [self.time setTextAlignment:NSTextAlignmentRight];

    [self.time setFont:[UIFont fontWithName:@"Heiti SC" size:12]];

    [self.border addSubview:self.time];
    
    [self.border addConstraint:[NSLayoutConstraint constraintWithItem:self.time attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.content attribute:NSLayoutAttributeBottom multiplier:1 constant:5]];
    [self.border addConstraint:[NSLayoutConstraint constraintWithItem:self.time attribute:NSLayoutAttributeLeading relatedBy:NSLayoutRelationEqual toItem:self.content attribute:NSLayoutAttributeTrailing multiplier:0.5 constant:0]];
    [self.border addConstraint:[NSLayoutConstraint constraintWithItem:self.time attribute:NSLayoutAttributeTrailing relatedBy:NSLayoutRelationEqual toItem:self.content attribute:NSLayoutAttributeTrailing multiplier:1 constant:0]];
    [self.border addConstraint:[NSLayoutConstraint constraintWithItem:self.time attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.border attribute:NSLayoutAttributeBottom multiplier:1 constant:-5]];
}

-(void)initIp {
    self.ip = [UILabel new];
    [self.ip setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.ip setText:self.message.ip];
    [self.ip setTextColor:[UIColor colorWithRed:0.09 green:0.09 blue:0.09 alpha:.70]];
//    [self.ip.layer setBorderColor:[UIColor colorWithRed:0 green:0 blue:0 alpha:.5].CGColor];
//    [self.ip.layer setBorderWidth:1.0f / [UIScreen mainScreen].scale];
    
    
    [self.ip setFont:[UIFont fontWithName:@"Heiti SC" size:12]];
    [self.border addSubview:self.ip];
    
    [self.border addConstraint:[NSLayoutConstraint constraintWithItem:self.ip attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.content attribute:NSLayoutAttributeBottom multiplier:1 constant:5]];
    [self.border addConstraint:[NSLayoutConstraint constraintWithItem:self.ip attribute:NSLayoutAttributeLeading relatedBy:NSLayoutRelationEqual toItem:self.content attribute:NSLayoutAttributeLeading multiplier:1 constant:0]];
    [self.border addConstraint:[NSLayoutConstraint constraintWithItem:self.ip attribute:NSLayoutAttributeTrailing relatedBy:NSLayoutRelationEqual toItem:self.content attribute:NSLayoutAttributeTrailing multiplier:0.5 constant:0]];
    [self.border addConstraint:[NSLayoutConstraint constraintWithItem:self.ip attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.border attribute:NSLayoutAttributeBottom multiplier:1 constant:-5]];
}
-(void)initMessage {
    self.content = [UILabel new];
    [self.content setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.content setText:self.message.content];
    [self.content setNumberOfLines:0];
    [self.content setTextColor:self.message.isAdmin ? [UIColor colorWithRed:0.67 green:0.19 blue:0.18 alpha:1.00] : [UIColor colorWithRed:0.09 green:0.09 blue:0.09 alpha:1.00]];
//    NSLog(@"%@", self.content.textColor);
    
//    [self.content.layer setBorderColor:[UIColor colorWithRed:0 green:0 blue:0 alpha:.5].CGColor];
//    [self.content.layer setBorderWidth:1.0f / [UIScreen mainScreen].scale];
//
    
    [self.border addSubview:self.content];

    [self.border addConstraint:[NSLayoutConstraint constraintWithItem:self.content attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.border attribute:NSLayoutAttributeTop multiplier:1 constant:5]];
    [self.border addConstraint:[NSLayoutConstraint constraintWithItem:self.content attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.avatar attribute:NSLayoutAttributeRight multiplier:1 constant:15.0]];
    [self.border addConstraint:[NSLayoutConstraint constraintWithItem:self.content attribute:NSLayoutAttributeRight relatedBy:NSLayoutRelationEqual toItem:self.border attribute:NSLayoutAttributeRight multiplier:1 constant:-10.0]];
    [self.border addConstraint:[NSLayoutConstraint constraintWithItem:self.content attribute:NSLayoutAttributeHeight relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:70]];
//    [self.border addConstraint:[NSLayoutConstraint constraintWithItem:self.content attribute:NSLayoutAttributeCenterX relatedBy:NSLayoutRelationEqual toItem:self.contentView attribute:NSLayoutAttributeCenterX multiplier:1.0 constant:0.0]];
//    [self.border addConstraint:[NSLayoutConstraint constraintWithItem:self.content attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.contentView attribute:NSLayoutAttributeCenterY multiplier:1.0 constant:0.0]];
}
- (void)initBorder {
    self.border = [UIView new];

    [self.border setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.border.layer setBackgroundColor:[UIColor colorWithRed:1 green:1 blue:1 alpha:1].CGColor];
//    
//    [self.border.layer setBorderColor:[UIColor colorWithRed:0 green:0 blue:0 alpha:.5].CGColor];
//    [self.border.layer setBorderWidth:1.0f / [UIScreen mainScreen].scale];

//    [self.border.layer setShadowColor:[UIColor colorWithRed:0.67 green:0.67 blue:0.67 alpha:1].CGColor];
//    [self.border.layer setShadowOffset:CGSizeMake(0, 1)];
//    [self.border.layer setShadowRadius:1.0f];
//    [self.border.layer setShadowOpacity:0.75f];
    
    [self.contentView addSubview:self.border];

    [self.contentView addConstraint:[NSLayoutConstraint constraintWithItem:self.border attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.contentView attribute:NSLayoutAttributeTop multiplier:1 constant:0]];
    [self.contentView addConstraint:[NSLayoutConstraint constraintWithItem:self.border attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.contentView attribute:NSLayoutAttributeBottom multiplier:1 constant:0]];
    [self.contentView addConstraint:[NSLayoutConstraint constraintWithItem:self.border attribute:NSLayoutAttributeLeading relatedBy:NSLayoutRelationEqual toItem:self.contentView attribute:NSLayoutAttributeLeading multiplier:1 constant:0.0]];
    [self.contentView addConstraint:[NSLayoutConstraint constraintWithItem:self.border attribute:NSLayoutAttributeTrailing relatedBy:NSLayoutRelationEqual toItem:self.contentView attribute:NSLayoutAttributeTrailing multiplier:1 constant:0.0]];
    [self.contentView addConstraint:[NSLayoutConstraint constraintWithItem:self.border attribute:NSLayoutAttributeCenterX relatedBy:NSLayoutRelationEqual toItem:self.contentView attribute:NSLayoutAttributeCenterX multiplier:1.0 constant:0.0]];
    [self.contentView addConstraint:[NSLayoutConstraint constraintWithItem:self.border attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.contentView attribute:NSLayoutAttributeCenterY multiplier:1.0 constant:0.0]];
}
- (void)awakeFromNib {
    // Initialization code
}

- (void)setSelected:(BOOL)selected animated:(BOOL)animated {
    [super setSelected:selected animated:animated];

    // Configure the view for the selected state
}

@end
